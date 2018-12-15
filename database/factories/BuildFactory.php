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

$factory->define(\TechnicPack\SolderFramework\Build::class, function (Faker $faker) {
    return [
        'tag'               => $faker->numerify('#.#.#'),
        'minecraft_version' => $faker->numerify('#.#.#'),
        'visibility'        => 'public',
        'modpack_id'        => function () {
            return factory(\TechnicPack\SolderFramework\Modpack::class)->create()->id;
        },
    ];
});

$factory->state(\TechnicPack\SolderFramework\Build::class, 'hidden', function (Faker $faker) {
    return [
        'visibility' => 'hidden',
    ];
});

$factory->state(\TechnicPack\SolderFramework\Build::class, 'private', function (Faker $faker) {
    return [
        'visibility' => 'private',
    ];
});

$factory->state(\TechnicPack\SolderFramework\Build::class, 'public', function (Faker $faker) {
    return [
        'visibility' => 'public',
    ];
});
