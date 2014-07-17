<?php

class GistFactory
{
    /**
     * Get new Gist
     *
     * @return Gist
     */
    public function getInstance()
    {
        return new Gist();
    }
}