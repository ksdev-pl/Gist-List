<?php

class GistCounterFactory
{
    /**
     * Get new GistCounter
     *
     * @param Gist[] $gists
     * @param int $ownerId
     *
     * @return GistCounter
     */
    public function getInstance(array $gists, $ownerId)
    {
        return new GistCounter($gists, $ownerId);
    }
}