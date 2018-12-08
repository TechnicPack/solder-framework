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

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\ModResource;
use TechnicPack\SolderFramework\Http\Legacy\BuildResource;

class BuildResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function transforms_data()
    {
        $build = factory(Build::class)->create([
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => 1024,
        ]);

        $resource = (new BuildResource($build))->jsonSerialize();

        $this->assertArraySubset([
            'minecraft' => '1.7.10',
            'java'      => '1.8',
            'memory'    => 1024,
        ], $resource);
    }

    /** @test **/
    public function has_a_collection_of_mods()
    {
        $build = factory(Build::class)->create();
        $build->dependencies()->saveMany([
            $dependencyA = factory(Dependency::class)->create(),
            $dependencyB = factory(Dependency::class)->create(),
        ]);

        $resource = (new BuildResource($build))->jsonSerialize();

        $this->assertCount(2, $resource['mods']);
        $this->assertInstanceOf(ModResource::class, $resource['mods'][0]);
        $this->assertInstanceOf(ModResource::class, $resource['mods'][1]);
    }
}
