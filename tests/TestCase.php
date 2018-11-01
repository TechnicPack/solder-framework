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

use Orchestra\Testbench\TestCase as BaseTestCase;
use TechnicPack\SolderFramework\SolderFrameworkServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SolderFrameworkServiceProvider::class,
        ];
    }
}
