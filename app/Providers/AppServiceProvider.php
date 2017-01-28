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
        $this->app->singleton(GistFinder::class, function () {
            $githubClient = new \Github\Client(
                new \Github\HttpClient\CachedHttpClient(['cache_dir' => storage_path('app/cache')])
            );
            $paginator = new \Github\ResultPager($githubClient);
            return new GistFinder($githubClient, $paginator);
        });
    }
}
