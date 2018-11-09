<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SolderFrameworkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register framework resources
        $this->registerRoutes();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'solder');
        $this->mergeConfigFrom(__DIR__.'/../config/solder.php', 'solder');

        // Publish framework assets
        $this->publishes([
            __DIR__.'/../config/solder.php' => config_path('solder.php'),
        ], 'solder-config');
    }

    /**
     * Register Solder's routes.
     */
    private function registerRoutes()
    {
        // If the routes have not been cached, we will include them in a route group
        // so that all of the routes will be conveniently registered to the given
        // controller namespace. After that we will load the Solder routes file.
        if (! $this->app->routesAreCached()) {
            Route::name('api.')
                ->namespace('TechnicPack\SolderFramework\Http\Controllers')
                ->prefix(Solder::$apiRoutePrefix)
                ->group(function ($router) {
                    require __DIR__.'/Http/routes.php';
                });

            // Catch-all Route...
            Route::view('/{any?}', Solder::$appBladeTemplate)
                ->where('any', '(.*)')
                ->middleware('web', 'auth')
                ->prefix(Solder::$appRoutePrefix)
                ->name('solder.index');
        }
    }
}
