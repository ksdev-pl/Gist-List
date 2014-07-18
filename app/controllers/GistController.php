<?php

class GistController extends Controller
{
    /** @type \GistFinder $gistFinder */
    private $gistFinder;

    /** @type \UserFinder $userFinder */
    private $userFinder;

    /** @type \GistBackupHandlerFactory $gistBackupHandlerFactory */
    private $gistBackupHandlerFactory;

    /**
     * @param GistFinder $gistFinder
     * @param UserFinder $userFinder
     * @param GistBackupHandlerFactory $gistBackupHandlerFactory
     */
    function __construct(
        GistFinder $gistFinder,
        UserFinder $userFinder,
        GistBackupHandlerFactory $gistBackupHandlerFactory
    )
    {
        $this->gistFinder = $gistFinder;
        $this->userFinder = $userFinder;
        $this->gistBackupHandlerFactory = $gistBackupHandlerFactory;
    }

    /**
     * Display a list of all Gists
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $gists = $this->gistFinder->getAll();
        $user = $this->userFinder->getAuthenticatedUser();
        $tags = Gist::getListOfCountedTags($gists, $user->getId());

        return View::make('gist.index', [
            'gists' => $gists,
            'tags' => $tags,
            'user' => $user
        ]);
    }

    /**
     * Backup Gists
     *
     * If the time of last backup is less than 5 minutes ago, interrupts and returns a string.
     * Otherwise returns the archived file response.
     *
     * @return string | \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function backup()
    {
        $user = $this->userFinder->getAuthenticatedUser();
        $userCanBackup = GistBackupHandler::checkLastBackupTime($user);
        if (!$userCanBackup) {
            return 'WAIT_A_MINUTE';
        }
        else {
            $gists = $this->gistFinder->getAll();
            $gistBackupHandler = $this->gistBackupHandlerFactory->getInstance($user, $gists);
            $zipPath = $gistBackupHandler->backup();

            return Response::download($zipPath, null, ['Set-Cookie' => 'fileDownload=true; path=/']);
        }
    }
}