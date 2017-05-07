<?php

namespace App\Helpers;

use App\Models\User;

class GistBackupHandler
{
    /** @var User $user */
    private $user;

    /** @var GistFinder $gistFinder */
    private $gistFinder;

    /** Max size in bytes of file that can be archived */
    const MAX_FILE_SIZE = 100000;

    function __construct(GistFinder $gistFinder)
    {
        $this->user = auth()->user();
        $this->gistFinder = $gistFinder;
    }

    /**
     * Backup gists.
     *
     * @return string|integer A path to gist archive or 0 if there are no files to backup.
     */
    public function backup()
    {
        $files = $this->prepareFiles();

        if (!count($files)) {
            return 0;
        }

        $zipPath = $this->zipFiles($files);

        $this->removeFiles();

        return $zipPath;
    }

    /**
     * Get each gist file and save it in the user folder.
     *
     * Also checks the size of a file to determine if it should be ignored.
     *
     * @return array Paths to gist files.
     */
    private function prepareFiles()
    {
        $gists = $this->gistFinder->fetchRawGists();

        $files = [];
        foreach ($gists as $gist) {
            foreach ($gist['files'] as $file) {
                if ($file['size'] < self::MAX_FILE_SIZE) {
                    $filename = $file['filename'];
                    $contents = $this->gistFinder->getGistFileContents($file['raw_url']);
                    $fileDate = date('YmdHis', strtotime($gist['updated_at']));
                    $filePath = "backups/{$this->user->id}/tmp/{$fileDate}-{$filename}";

                    \Storage::put($filePath, $contents);

                    $files[] = storage_path("app/$filePath");
                }
            }
        }

        return $files;
    }

    /**
     * Create a zip archive and add files to it.
     *
     * @param array $files
     *
     * @return string Path to zip archive.
     *
     * @throws \Exception If the archive cannot be created.
     */
    private function zipFiles(array $files)
    {
        $zipName = date("YmdHis") . '-gists-' . str_slug($this->user->nickname);
        $zipPath = storage_path("app/backups/{$this->user->id}/{$zipName}.zip");

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }

            $zip->close();

            return $zipPath;
        } else {
            throw new \Exception("Cannot open $zipPath for writing.");
        }
    }

    /**
     * Delete the previously prepared files.
     */
    private function removeFiles()
    {
        \Storage::deleteDirectory("backups/{$this->user->id}/tmp");
    }
}
