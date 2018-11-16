<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\QueryBuilder\QueryBuilderServiceProvider;
use Orchestra\Testbench\Http\Middleware\Authenticate;
use TechnicPack\SolderFramework\SolderFrameworkServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');

        // TODO: need testbench 3.7.5 to remove this hack
        Route::get('/login', function () { })->name('login');

        $this->withoutMiddleware([
            Authenticate::class,
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SolderFrameworkServiceProvider::class,
            QueryBuilderServiceProvider::class,
        ];
    }
}
