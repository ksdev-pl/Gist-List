<?php

class UserController extends Controller
{
    /** @type \AbstractGithubApi $githubApi */
    private $githubApi;

    /**
     * @param AbstractGithubApi $githubApi
     */
    function __construct(AbstractGithubApi $githubApi)
    {
        $this->githubApi = $githubApi;
    }

    /**
     * Authenticate & sign User in
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signIn()
    {
        return $this->githubApi->requestGithubAccess('gist');
    }

    /**
     * Get GitHub API access token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAccessToken()
    {
        $this->githubApi->getAccessToken();

        return Redirect::secure('/gists');
    }

    /**
     * Get current rate limit status
     *
     * @return array
     */
    public function getRateLimit()
    {
        $rateLimit = $this->githubApi->getRateLimit();

        return $rateLimit;
    }

    /**
     * Sign User out
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signOut()
    {
        Session::flush();

        return Redirect::secure('/');
    }
}
