<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure the disks to be used for storing different types
    | of files used and served by the framework.
    */

    'disk' => [
        'icons' => env('FILESYSTEM_ICONS', 'public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Runtime Models
    |--------------------------------------------------------------------------
    |
    | Here you can override the class names of the jobs used by this package.
    | Make sure your custom models extend the ones provided by the package.
    */

    'model' => [
        'modpack' => \TechnicPack\SolderFramework\Modpack::class,
        'build'   => \TechnicPack\SolderFramework\Build::class,
        'mod'     => \TechnicPack\SolderFramework\Mod::class,
    ],
];
