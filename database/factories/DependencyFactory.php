<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Faker\Generator as Faker;

$factory->define(\TechnicPack\SolderFramework\Dependency::class, function (Faker $faker) {
    return [
        'build_id'  => function () {
            return factory(\TechnicPack\SolderFramework\Build::class)->create()->id;
        },
        'version_id' => function () {
            return factory(\TechnicPack\SolderFramework\Version::class)->create()->id;
        },
    ];
});
