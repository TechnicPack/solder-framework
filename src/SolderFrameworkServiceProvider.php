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

use Illuminate\Support\ServiceProvider;

class SolderFrameworkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Define framework resources
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/solder.php', 'solder');

        // Publish framework assets
        $this->publishes([
            __DIR__.'/../config/solder.php' => config_path('solder.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
