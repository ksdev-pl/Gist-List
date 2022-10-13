<?php

namespace App\Providers;

use App\Helpers\GistFinder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);

        $this->app->singleton(GistFinder::class, function () {
            $githubClient = new \Github\Client();

            $paginator = new \Github\ResultPager($githubClient);

            $guzzleClient = new \GuzzleHttp\Client();

            return new GistFinder($githubClient, $paginator, $guzzleClient);
        });
    }
}
