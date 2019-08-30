<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\SocialUserResolver;
use Coderello\SocialGrant\Resolvers\SocialUserResolverInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public $bindings = [
        SocialUserResolverInterface::class => SocialUserResolver::class,
    ];


    public function boot()
    {
        Schema::defaultStringLength(191);
        $url = $this->app['url'];
        $url->forceRootUrl(config('app.url'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
