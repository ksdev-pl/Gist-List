<?php

use GuzzleHttp\Client;

class GuzzleGithubApi extends AbstractGithubApi
{
    /** @type GuzzleHttp\Client $client */
    private $client;

    /**
     * Prepare the Guzzle client
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new GuzzleHttp\Client();
    }

    /**
     * Exchange code received after requesting GitHub access for an access token
     *
     * Use the access token to access the API
     */
    public function getAccessToken()
    {
        $code = Input::get('code');

        $clientId = $this->config['client_id'];
        $clientSecret = $this->config['client_secret'];

        $response = $this->client->post('https://github.com/login/oauth/access_token', [
            'headers' => ['Accept' => 'application/json'],
            'body' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code
            ]
        ])->json();

        Session::put('accessToken', $response['access_token']);
        Session::regenerate();
    }

    /**
     * Get the access token stored in session
     *
     * @return string
     *
     * @throws NoSessionToken
     */
    private function getSessionToken()
    {
        if (Session::has('accessToken')) {
            return Session::get('accessToken');
        }
        else {
            throw new NoSessionToken();
        }
    }

    /**
     * Get Gists of authenticated user
     *
     * @param boolean $starred  If true, returns starred gists. Defaults to false
     *
     * @return array
     */
    public function getGistsOfAuthUser($starred = false)
    {
        if ($starred) {
            $url = 'https://api.github.com/gists/starred';
        }
        else {
            $url = 'https://api.github.com/gists';
        }

        $response = $this->client->get($url, [
            'headers' => ['Authorization' => 'token ' . $this->getSessionToken()],
            'query' => ['per_page' => '100']
        ]);

        $gists = $response->json();

        if ($response->hasHeader('link')) {
            $linkHeader = $response->getHeader('link');
            $gistsFromAdditionalPages = $this->getMergedGistsFromAdditionalPages($linkHeader);
            $gists = array_merge($gists, $gistsFromAdditionalPages);
        }

        return $gists;
    }

    /**
     * Get merged Gists from additional pages
     *
     * @param string $linkHeader  Link header from GitHub API response
     *
     * @return array
     */
    private function getMergedGistsFromAdditionalPages($linkHeader)
    {
        $linkArray = explode(',', $linkHeader);
        $gistsFromAdditionalPages = [];
        foreach ($linkArray as $link) {
            if (strpos($link, 'next') !== false) {
                $urlPart = explode(';', $link)[0];
                $url = substr($urlPart, 1, -1);

                $nextPageResponse = $this->client->get($url, [
                    'headers' => ['Authorization' => 'token ' . $this->getSessionToken()]
                ]);
                $nextPageGists = $nextPageResponse->json();

                $gistsFromAdditionalPages = array_merge($gistsFromAdditionalPages, $nextPageGists);
            }
        }

        return $gistsFromAdditionalPages;
    }

    /**
     * Get the currently authenticated user
     *
     * @return array
     */
    public function getAuthenticatedUser()
    {
        $response = $this->client->get('https://api.github.com/user', [
            'headers' => ['Authorization' => 'token ' . $this->getSessionToken()]
        ])->json();

        return $response;
    }

    /**
     * Get the contents of a Gist file
     *
     * @param $url
     *
     * @return \GuzzleHttp\Stream\StreamInterface | null
     */
    public function getGistFileContents($url)
    {
        $response = $this->client->get($url)->getBody();

        return $response;
    }

    /**
     * Get a single Gist
     *
     * User must be authenticated
     *
     * @param $id
     *
     * @return array
     */
    public function getSingleGistOfAuthUser($id)
    {
        $response = $this->client->get('https://api.github.com/gists/' . $id, [
            'headers' => ['Authorization' => 'token ' . $this->getSessionToken()]
        ])->json();

        return $response;
    }

    /**
     * Get current rate limit status
     *
     * User must be authenticated
     *
     * @return array
     */
    public function getRateLimit()
    {
        $response = $this->client->get('https://api.github.com/rate_limit', [
            'headers' => ['Authorization' => 'token ' . $this->getSessionToken()]
        ])->json();

        return $response;
    }
}