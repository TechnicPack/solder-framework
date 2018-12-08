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

use Illuminate\Support\Collection;
use TechnicPack\SolderFramework\Build;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\ModpackResource;

class ModpackResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function transforms_data()
    {
        $filesystem = Storage::disk(config('solder.disk.icons'));
        $modpack = factory(Modpack::class)->create([
            'name'         => 'Example Modpack',
            'slug'         => 'example-modpack',
            'url'          => 'http://example.com',
            'icon_path'    => 'example-icon.png',
        ]);

        $resource = (new ModpackResource($modpack))->jsonSerialize();

        $this->assertArraySubset([
            'name'         => 'example-modpack',
            'display_name' => 'Example Modpack',
            'url'          => 'http://example.com',
            'icon'         => $filesystem->url('example-icon.png'),
            'latest'       => null,
            'recommended'  => null,
        ], $resource);
    }

    /** @test **/
    public function has_a_collection_of_build_tags()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->saveMany([
            factory(Build::class)->make(['tag' => '1.0.0']),
            factory(Build::class)->make(['tag' => '2.0.0']),
        ]);

        $resource = (new ModpackResource($modpack))->jsonSerialize();

        $this->assertInstanceOf(Collection::class, $resource['builds']);
        $this->assertCount(2, $resource['builds']);
        $this->assertArraySubset(['1.0.0', '2.0.0'], $resource['builds']);
    }
}
