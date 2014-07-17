<?php

class GistBackupHandler
{
    /** @type AbstractGithubApi $githubApi */
    private $githubApi;

    /** @type Gist[] $gists */
    private $gists;

    /** @type User $user */
    private $user;

    /** @type string string */
    private $userFolderPath;

    /** Max size in bytes of Gist that can be archived */
    const MAX_FILE_SIZE = 100000;

    /**
     * @param AbstractGithubApi $githubApi
     * @param User $user
     * @param Gist[] $gists
     */
    function __construct(AbstractGithubApi $githubApi, User $user, array $gists)
    {
        $this->githubApi = $githubApi;
        $this->user = $user;
        $this->gists = $gists;

        $this->userFolderPath = storage_path() . '/backups/' . $user->getId();
    }

    /**
     * Backup gists
     *
     * @return string  A path to Gist archive
     */
    public function backup()
    {
        $this->prepareDirectories();

        $files = $this->saveGistsInDirectory();

        $zipPath = $this->zipFiles($files);

        $this->removeFiles($files);

        return $zipPath;
    }

    /**
     * Check last backup time
     *
     * Checks the modification time of files in authenticated user archive folder and if one of them is less than
     * 5 minutes ago.
     *
     * @param User $user
     *
     * @return bool  False if last backup was made less than 5 minutes ago, true otherwise
     */
    public static function checkLastBackupTime(User $user)
    {
        $zipFiles = File::files(storage_path() . '/backups/' . $user->getId() . '/zip/');
        foreach ($zipFiles as $zipFile) {
            $timeOfBackup = File::lastModified($zipFile);
            if ($timeOfBackup > strtotime('-5 minutes')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create directories necessary for backup process
     */
    private function prepareDirectories()
    {
        if (!File::isDirectory($this->userFolderPath)) {
            File::makeDirectory($this->userFolderPath, 0775);
        }
        if (!File::isDirectory($this->userFolderPath . '/files')) {
            File::makeDirectory($this->userFolderPath . '/files', 0775);
        }
        if (!File::isDirectory($this->userFolderPath . '/zip')) {
            File::makeDirectory($this->userFolderPath . '/zip', 0775);
        }
    }

    /**
     * Get each Gist file and save it in directory
     *
     * Also checks the size of a file to determine if it should be ignored
     *
     * @return array  Paths to Gist files
     */
    private function saveGistsInDirectory()
    {
        $files = [];
        foreach ($this->gists as $gist) {
            /** @type Gist $gist */
            foreach ($gist->getFiles() as $file) {
                if ($file['size'] < self::MAX_FILE_SIZE) {
                    $fileName = date('YmdHis', strtotime($gist->getUpdatedAt())) . '-' . $file['filename'];
                    $filePath = $this->userFolderPath . '/files/' . $fileName;
                    $contents = $this->githubApi->getGistFileContents($file['raw_url']);
                    File::put($filePath, $contents);

                    $files[] = $filePath;
                }
            }
        }

        return $files;
    }

    /**
     * Create zip archive and add files to it
     *
     * @param array $files
     *
     * @return string  Path to zip archive
     *
     * @throws Exception  If the archive cannot be created
     */
    private function zipFiles(array $files)
    {
        $zipName = date("YmdHis") . '-gists-' . $this->user->getLogin();
        $zipPath = $this->userFolderPath . '/zip/' . $zipName . '.zip';

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }

            $zip->close();

            return $zipPath;
        } else {
            throw new Exception('Cannot open ' . $zipPath . ' for writing.');
        }
    }

    /**
     * Delete all Gist files
     */
    private function removeFiles($files)
    {
        File::delete($files);
    }
}