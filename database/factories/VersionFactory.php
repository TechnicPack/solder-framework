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

$factory->define(\TechnicPack\SolderFramework\Version::class, function (Faker $faker) {
    return [
        'tag'               => $faker->numerify('#.#.#'),
        'mod_id'            => function () {
            return factory(\TechnicPack\SolderFramework\Mod::class)->create()->id;
        },
    ];
});

$factory->state(\TechnicPack\SolderFramework\Version::class, 'with-package', function (Faker $faker) {
    $file = \Illuminate\Http\UploadedFile::fake()->create('example_package-1.0.0.zip');

    return [
        'package'      => $file->store('files', config('solder.disk.files')),
        'package_name' => $file->getClientOriginalName(),
        'package_size' => $file->getSize(),
        'package_hash' => $file->getHash(),
    ];
});
