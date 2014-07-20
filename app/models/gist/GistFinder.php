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
        $userGists = $this->githubApi->getGistsOfAuthUser();
        $starredGists = $this->githubApi->getGistsOfAuthUser(true);

        $mergedGists =  $this->mergeGistArrays($userGists, $starredGists);

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
        $gistObject->setOwner($gistArray['owner']);
        $gistObject->setDescriptionAndTags($gistArray['description']);
        $gistObject->setCreatedAt($gistArray['created_at']);
        $gistObject->setUpdatedAt($gistArray['updated_at']);
        $gistObject->setIsPublic($gistArray['public']);
        $gistObject->setHtmlUrl($gistArray['html_url']);
        $gistObject->setFiles($gistArray['files']);
        $gistObject->setIsStarred($gistArray['starred']);

        return $gistObject;
    }

    /**
     * Merge arrays of user owned and starred gists and remove duplicates
     *
     * If a gist is at the same time owned and starred by the user, only the starred gist remains in merged array.
     * Also adds to every gist a 'starred' key with boolean value.
     *
     * @param array $arrayOfUserGists
     * @param array $arrayOfStarredGists
     *
     * @return array
     */
    private function mergeGistArrays(array $arrayOfUserGists, array $arrayOfStarredGists)
    {
        if (!empty($arrayOfUserGists)) {
            $authUserId = $arrayOfUserGists[0]['owner']['id'];

            $idsOfStarredGistsOwnedByUser = [];
            foreach ($arrayOfStarredGists as $key => $starredGist) {
                if ($starredGist['owner']['id'] === $authUserId) {
                    $idsOfStarredGistsOwnedByUser[] = $starredGist['id'];
                }
                $arrayOfStarredGists[$key]['starred'] = true;
            }

            foreach ($arrayOfUserGists as $key => $userGist) {
                foreach ($idsOfStarredGistsOwnedByUser as $starredId) {
                    if ($userGist['id'] === $starredId) {
                        unset ($arrayOfUserGists[$key]);

                        continue 2;
                    }
                }

                $arrayOfUserGists[$key]['starred'] = false;
            }
        }
        elseif (!empty($arrayOfStarredGists)) {
            foreach ($arrayOfStarredGists as $key => $starredGist) {
                $arrayOfStarredGists[$key]['starred'] = true;
            }
        }

        $arrayOfMergedGists = array_merge($arrayOfUserGists, $arrayOfStarredGists);

        return $arrayOfMergedGists;
    }
}