<?php

namespace App\Http\Controllers;

use App\Helpers\GistBackupHandler;
use App\Helpers\GistFinder;
use Carbon\Carbon;

class GistsController extends Controller
{
    /**
     * Show gists list.
     *
     * @param GistFinder $gistFinder
     *
     * @return \Illuminate\View\View
     */
    public function index(GistFinder $gistFinder)
    {
        $gistsAndTags = $gistFinder->getGistsAndTags();

        $authUser = auth()->user();
        $state = collect([
            'user'  => [
                'name'     => $authUser->name,
                'nickname' => $authUser->nickname,
                'avatar'   => $authUser->avatar
            ],
            'gists' => $gistsAndTags['gists'],
            'tags'  => $gistsAndTags['tags']
        ]);

        return view('gists.index', compact('state'));
    }

    /**
     * Backup authorized user gists.
     *
     * @param GistBackupHandler $backupHandler
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function backup(GistBackupHandler $backupHandler)
    {
        if (auth()->user()->gists_backup_at) {
            $lastBackup = Carbon::createFromFormat('Y-m-d H:i:s', auth()->user()->gists_backup_at);
            if ($lastBackup->gt(Carbon::now()->subMinutes(5))) {
                return response('WAIT_A_MINUTE', 429);
            };
        }

        $zipPath = $backupHandler->backup();

        if ($zipPath === 0) {
            return response('NO_FILES', 404);
        }

        auth()->user()->update(['gists_backup_at' => date('Y-m-d H:i:s')]);

        return response()->download($zipPath, null, [
            'Set-Cookie'   => 'fileDownload=true; path=/',
            'Content-Type' => 'application/zip'
        ])->deleteFileAfterSend(true);
    }
}
