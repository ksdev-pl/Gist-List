<?php

class GistFinder
{
    /**
     * @type AbstractGithubApi
     */
    private $githubApi;

    /**
     * @type GistFactory
     */
    private $gistFactory;

    /**
     * @param AbstractGithubApi $githubApi
     * @param GistFactory $gistFactory
     */
    function __construct(AbstractGithubApi $githubApi, GistFactory $gistFactory)
    {
        $this->githubApi = $githubApi;
        $this->gistFactory = $gistFactory;
    }

    /**
     * Get all Gists
     *
     * @return Gist[]
     */
    public function getAll()
    {
        $userGists = $this->addStarredValue(false, $this->githubApi->getGistsOfAuthUser());
        $starredGists = $this->addStarredValue(true, $this->githubApi->getGistsOfAuthUser(true));

        $mergedGists = array_merge($userGists, $starredGists);

        if (!empty($userGists) && !empty($starredGists)) {
            $mergedGists = $this->removeDuplicates($mergedGists);
        }

        $arrayOfGistObjects = [];
        foreach ($mergedGists as $gist) {
            $gistObject = $this->load($gist);
            $arrayOfGistObjects[] = $gistObject;
        }

        return $arrayOfGistObjects;
    }

    /**
     * Get a single Gist
     *
     * @param int $id
     *
     * @return Gist
     */
    public function getOne($id)
    {
        $gist = $this->githubApi->getSingleGistOfAuthUser($id);
        $gistObject = $this->load($gist);

        return $gistObject;
    }

    /**
     * Hydrate Gist object
     *
     * @param array $gistArray
     *
     * @return Gist
     */
    private function load($gistArray)
    {
        $gistObject = $this->gistFactory->getInstance();
        $gistObject->setId($gistArray['id']);
        $gistObject->setOwner(
            isset($gistArray['owner']) ? $gistArray['owner'] : ['id' => null, 'login' => 'anonymous']
        );
        $gistObject->setDescriptionAndTags($gistArray['description']);
        $gistObject->setCreatedAt($gistArray['created_at']);
        $gistObject->setUpdatedAt($gistArray['updated_at']);
        $gistObject->setPublic($gistArray['public']);
        $gistObject->setHtmlUrl($gistArray['html_url']);
        $gistObject->setFiles($gistArray['files']);
        $gistObject->setStarred($gistArray['starred']);

        return $gistObject;
    }

    /**
     * Add to every gist a 'starred' key with boolean value.
     *
     * @param bool $isStarred  Defaults to true
     * @param array $gistsArray  Array of gist api responses
     *
     * @return array
     */
    private function addStarredValue($isStarred = true, array $gistsArray)
    {
        foreach ($gistsArray as &$gist) {
            $gist['starred'] = $isStarred;
        }

        return $gistsArray;
    }

    /**
     * Remove duplicates from gists array
     *
     * If a gist is at the same time owned (unstarred) and starred by the user, only the starred gist remains
     * in resultant array.
     *
     * @param array $gists
     *
     * @return array
     */
    private function removeDuplicates(array $gists)
    {
        $userId = $this->findUnstarredGistsOwnerId($gists);

        $userStarredGistsIds = [];

        // Loop starred gists
        foreach ($gists as $gist) {
            if ($gist['starred'] && isset($gist['owner']) && $gist['owner']['id'] === $userId) {
                $userStarredGistsIds[] = $gist['id'];
            }
        }

        // Loop owned (unstarred) gists
        foreach ($gists as $key => $gist) {
            if (!$gist['starred']) {

                foreach ($userStarredGistsIds as $starredId) {
                    if ($gist['id'] === $starredId) {
                        unset ($gists[$key]);

                        break;
                    }
                }
            }
        }

        return $gists;
    }

    /**
     * Find id of the owner of unstarred gists
     *
     * @param array $gists
     *
     * @return int
     */
    private function findUnstarredGistsOwnerId($gists)
    {
        foreach ($gists as $gist) {
            if (!$gist['starred']) {
                return $gist['owner']['id'];
            }
        }
    }
}
