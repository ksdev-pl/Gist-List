<?php

abstract class AbstractGithubApi
{
    /** @type array $config */
    protected $config = [];

    /**
     * Prepares config array
     */
    public function __construct()
    {
        $this->config['client_id'] = Config::get('github.client_id');
        $this->config['client_secret'] = Config::get('github.client_secret');
    }

    /**
     * Request GitHub access
     *
     * @param string $scope  A comma separated list of scopes
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestGithubAccess($scope)
    {
        $clientId    = $this->config['client_id'];
        $redirectUri = secure_url('/getaccesstoken');
        $state       = csrf_token();

        return Redirect::to(
            'https://github.com/login/oauth/authorize?client_id=' . $clientId
            . '&redirect_uri=' . $redirectUri
            . '&scope=' . $scope
            . '&state=' . $state
        );
    }

    /**
     * Exchange code received after requesting GitHub access for an access token
     *
     * Use the access token to access the API
     */
    abstract function getAccessToken();

    /**
     * Get Gists of authenticated user
     *
     * @param boolean $starred  If true, returns starred gists. Defaults to false
     *
     * @return array
     */
    abstract function getGistsOfAuthUser($starred = false);

    /**
     * Get the currently authenticated user
     *
     * @return array
     */
    abstract function getAuthenticatedUser();

    /**
     * Get the contents of a Gist file
     *
     * @param $url
     *
     * @return mixed
     */
    abstract function getGistFileContents($url);

    /**
     * Get a single Gist
     *
     * User must be authenticated
     *
     * @param $id
     *
     * @return array
     */
    abstract function getSingleGistOfAuthUser($id);

    /**
     * Get current rate limit status
     *
     * User must be authenticated
     *
     * @return array
     */
    abstract function getRateLimit();
}