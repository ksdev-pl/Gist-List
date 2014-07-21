<?php

class Gist
{
    /** @type int $id */
    private $id;

    /** @type array $owner */
    private $owner = [];

    /** @type string $description */
    private $description;

    /** @type array $tags */
    private $tags = [];

    /** @type string $createdAt */
    private $createdAt;

    /** @type string $updatedAt */
    private $updatedAt;

    /** @type array $files */
    private $files = [];

    /** @type boolean $public */
    private $public;

    /** @type boolean $starred */
    private $starred;

    /** @type string $htmlUrl */
    private $htmlUrl;

    /**
     * Convert date to 'Y-m-d H:i:s' format
     *
     * @param string $date
     *
     * @return string
     */
    private function convertDate($date)
    {
        $convertedDate = date('Y-m-d H:i:s', strtotime($date));

        return $convertedDate;
    }

    /**
     * Set Gist description and tags
     *
     * Seeks tags in original Gist description and saves them and description separately
     *
     * @param string $description  Gist description with optional tags
     */
    public function setDescriptionAndTags($description)
    {
        $tags = [];
        $descriptionWithoutTags = trim(
            preg_replace_callback(
                '~#\w+~',
                function($matches) use (&$tags) {
                    $tags[] = $matches[0];
                },
                $description
            )
        );

        $this->setDescription($descriptionWithoutTags);
        $this->setTags($tags);
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return array
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->convertDate($createdAt);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $description
     */
    private function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $tags
     */
    private function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $this->convertDate($updatedAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param boolean $isPublic
     */
    public function setPublic($isPublic)
    {
        $this->public = $isPublic;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param string $htmlUrl
     */
    public function setHtmlUrl($htmlUrl)
    {
        $this->htmlUrl = $htmlUrl;
    }

    /**
     * @param boolean $isStarred
     */
    public function setStarred($isStarred)
    {
        $this->starred = $isStarred;
    }

    /**
     * @return boolean
     */
    public function isStarred()
    {
        return $this->starred;
    }

    /**
     * @return string
     */
    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }
}