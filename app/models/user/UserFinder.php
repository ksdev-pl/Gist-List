<?php

class UserFinder
{
    /**
     * @type AbstractGithubApi
     */
    private $githubApi;

    /**
     * @type UserFactory
     */
    private $userFactory;

    /**
     * @param AbstractGithubApi $githubApi
     * @param UserFactory $userFactory
     */
    function __construct(AbstractGithubApi $githubApi, UserFactory $userFactory)
    {
        $this->githubApi = $githubApi;
        $this->userFactory = $userFactory;
    }

    /**
     * Get authenticated User
     *
     * @return User
     */
    public function getAuthenticatedUser()
    {
        $authenticatedUser = $this->githubApi->getAuthenticatedUser();
        $authenticatedUserObject = $this->load($authenticatedUser);

        return $authenticatedUserObject;
    }

    /**
     * Hydrate User object
     *
     * @param $userArray
     *
     * @return User
     */
    private function load($userArray)
    {
        $userObject = $this->userFactory->getInstance();
        $userObject->setId($userArray['id']);
        if (isset($userArray['name'])) {
            $userObject->setName($userArray['name']);
        }
        else {
            $userObject->setName('No name');
        }
        $userObject->setLogin($userArray['login']);
        $userObject->setAvatarUrl($userArray['avatar_url']);
        $userObject->setHtmlUrl($userArray['html_url']);

        return $userObject;
    }
}
