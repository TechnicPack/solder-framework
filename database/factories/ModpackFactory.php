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

$factory->define(\TechnicPack\SolderFramework\Modpack::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
        'slug' => $faker->slug,
    ];
});

$factory->state(\TechnicPack\SolderFramework\Modpack::class, 'with-icon', function (Faker $faker) {
    \Illuminate\Support\Facades\Storage::fake(config('solder.disk.icons'));
    $file = \Illuminate\Http\UploadedFile::fake()->image('modpack-icon.png');

    return [
        'icon_path' => $file->store('icons', config('solder.disk.icons')),
    ];
});
