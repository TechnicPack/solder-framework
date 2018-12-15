<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Build;

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ShowBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function an_individual_build_can_be_shown()
    {
        $build = factory(Build::class)->state('hidden')->create([
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
        ]);

        $response = $this->getJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'id'                => $build->id,
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
            'visibility'        => 'hidden',
            'created_at'        => $build->created_at->format('Y-m-d H:i:s'),
            'updated_at'        => $build->updated_at->format('Y-m-d H:i:s'),
        ]);
    }

    /** @test **/
    public function showing_a_build_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $build = factory(Build::class)->create();

        $response = $this->getJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function showing_an_invalid_build_returns_a_404_error()
    {
        $response = $this->getJson('/api/modpacks/99/builds/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function the_child_dependency_data_can_be_included()
    {
        $build = factory(Build::class)->create();
        $version = factory(Version::class)->create();
        $dependency = Dependency::create([
            'build_id'   => $build->id,
            'version_id' => $version->id,
        ]);

        $response = $this->getJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}?include=dependencies.version,dependencies.mod");

        $response->assertStatus(200);
        $response->assertJsonStructure(['dependencies' => [['mod', 'version']]]);
        $response->assertJsonCount(1, 'dependencies');
        $response->assertJsonFragment([
            'id'         => $dependency->id,
            'created_at' => $dependency->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $dependency->updated_at->format('Y-m-d H:i:s'),
        ]);
        $response->assertJsonFragment([
            'id'  => $version->id,
            'tag' => $version->tag,
        ]);
        $response->assertJsonFragment([
            'id'    => $version->mod->id,
            'modid' => $version->mod->modid,
        ]);
    }
}
