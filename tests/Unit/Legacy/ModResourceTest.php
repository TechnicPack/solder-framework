<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Unit\Legacy;

use TechnicPack\SolderFramework\Mod;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\ModResource;

class ModResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function transforms_data()
    {
        $filesystem = Storage::disk(config('solder.disk.files'));
        $mod = factory(Mod::class)->create([
            'modid' => 'examplemod',
        ]);
        $version = factory(Version::class)->create([
            'tag'          => '1.0.0',
            'package_path' => 'example-package.zip',
            'package_hash' => 'example-hash',
            'mod_id'       => $mod->id,
        ]);
        $dependency = factory(Dependency::class)->create([
            'version_id' => $version->id,
        ]);

        $resource = (new ModResource($dependency))->jsonSerialize();

        $this->assertArraySubset([
            'name'        => 'examplemod',
            'version'     => '1.0.0',
            'md5'         => 'example-hash',
            'url'         => $filesystem->url('example-package.zip'),
        ], $resource);
    }

    /** @test **/
    public function includes_additional_mod_data_based_on_request()
    {
        $mod = factory(Mod::class)->create([
            'name'        => 'Example Mod',
            'author'      => 'John Doe',
            'description' => 'This is an example mod.',
            'url'         => 'http://example.com',
        ]);
        $version = factory(Version::class)->create(['mod_id' => $mod->id]);
        $dependency = factory(Dependency::class)->create(['version_id' => $version->id]);

        request()->query->add(['include' => 'mods']);
        $resource = (new ModResource($dependency))->jsonSerialize();

        $this->assertArraySubset([
            'pretty_name' => 'Example Mod',
            'author'      => 'John Doe',
            'description' => 'This is an example mod.',
            'link'        => 'http://example.com',
        ], $resource);
    }
}
