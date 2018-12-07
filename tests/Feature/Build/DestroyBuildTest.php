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
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_build_can_be_destroyed()
    {
        $build = factory(Build::class)->create();

        $response = $this->deleteJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Build::all());
    }

    /** @test **/
    public function destroying_a_build_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $build = factory(Build::class)->create();

        $response = $this->deleteJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Build::all());
    }

    /** @test */
    public function destroying_an_invalid_build_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/modpacks/99/builds/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function destroying_a_build_removes_related_dependencies()
    {
        $build = factory(Build::class)->create();
        $build->dependencies()->save(factory(Dependency::class)->make(['build_id' => $build->id]));

        $response = $this->deleteJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Dependency::all());
    }
}
