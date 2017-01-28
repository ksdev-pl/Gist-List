<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

class GithubAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')
            ->scopes(['gist'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect()->action('Auth\AuthController@redirectToProvider');
        }

        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);

        return redirect()->action('GistsController@index');
    }

    /**
     * Create and return user if doesn't exist. Update and return otherwise.
     *
     * @param $githubUser
     * @return User
     */
    private function findOrCreateUser($githubUser)
    {
        $user = User::firstOrCreate(['github_id' => $githubUser->getId()]);
        $user->fill([
            'name'     => $githubUser->getName(),
            'nickname' => $githubUser->getNickname(),
            'avatar'   => $githubUser->getAvatar(),
            'token'    => $githubUser->token
        ]);

        $user->save();

        return $user;
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->action('PagesController@home');
    }
}
