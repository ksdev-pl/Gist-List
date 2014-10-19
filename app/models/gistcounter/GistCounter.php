<?php

class GistCounter
{
    /** @type Gist[] $gists */
    private $gists;

    /** @type int $ownerId */
    private $ownerId;

    /** @type int $public */
    private $public = 0;

    /** @type int $private */
    private $private = 0;

    /** @type int $withoutTag */
    private $withoutTag = 0;

    /** @type int $owned */
    private $owned = 0;

    /** @type int $starred */
    private $starred = 0;

    /** @type int $all */
    private $all = 0;

    /** @type array $tags */
    private $tags = [];

    /**
     * Count Gists on instantiation
     *
     * @param Gist[] $gists
     * @param int $ownerId  GitHub id of owner of Gists
     */
    public function __construct(array $gists, $ownerId)
    {
        $this->gists = $gists;
        $this->ownerId = $ownerId;

        $this->countGists();
    }

    /**
     * Count Gists
     *
     * Counts the number of each tag occurences, of Gists with no tag, of public, private, user own, starred
     * and all Gists together.
     */
    private function countGists()
    {
        foreach ($this->gists as $gist) {

            if ($gist->isPublic()) {
                $this->public += 1;
            }
            else {
                $this->private += 1;
            }

            if (!$gist->getTags()) {
                $this->withoutTag += 1;
            }

            if ($gist->getOwner()['id'] === $this->ownerId) {
                $this->owned += 1;
            }

            if ($gist->isStarred() === true) {
                $this->starred += 1;
            }

            $this->all += 1;

            $this->countTags($gist->getTags());
        }
    }

    /**
     * Count tags
     *
     * @param array $tags
     */
    private function countTags(array $tags)
    {
        foreach ($tags as $tagFromGist) {

            $tagAlreadyCounted = false;
            foreach ($this->tags as $key => $tag) {
                if ($tag['name'] === $tagFromGist) {
                    $tagAlreadyCounted = $key;
                }
            }

            if ($tagAlreadyCounted === false) {
                $this->tags[] = ['name' => $tagFromGist, 'count' => 1];
            }
            else {
                $this->tags[$tagAlreadyCounted]['count'] += 1;
            }
        }

        $this->sortTagsByName();
    }

    /**
     * Sort tags by name
     */
    private function sortTagsByName()
    {
        foreach ($this->tags as $key => $row) {
            $name[$key]  = $row['name'];
        }

        array_multisort($name, SORT_NATURAL | SORT_FLAG_CASE, $this->tags);
    }

    /**
     * @return int
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * @return int
     */
    public function getOwned()
    {
        return $this->owned;
    }

    /**
     * @return int
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @return int
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @return int
     */
    public function getStarred()
    {
        return $this->starred;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return int
     */
    public function getWithoutTag()
    {
        return $this->withoutTag;
    }
}