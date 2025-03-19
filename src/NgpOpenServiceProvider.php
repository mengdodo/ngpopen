<?php

namespace Mengdodo\Ngpopen;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mengdodo\Ngpopen\Library\NgpOpenSdk;

class NgpOpenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ngpSdk', fn(Application $app) => new NgpOpenSdk());
        $this->mergeConfigFrom(__DIR__.'/config/ngp.php', 'ngp');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([__DIR__.'/config/ngp.php' => config_path('ngp.php')], 'ngpConfig');
    }
}
