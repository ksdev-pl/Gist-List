<?php

class GistBackupHandlerFactory
{
    /**
     * Get new GistBackupHandler
     *
     * @param User $user
     * @param Gist[] $gists
     *
     * @return GistBackupHandler
     */
    public function getInstance(User $user, array $gists)
    {
        return new GistBackupHandler(App::make('GuzzleGithubApi'), $user, $gists);
    }
}
