<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Version;

use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyVersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_version_can_be_destroyed()
    {
        $version = factory(Version::class)->create();

        $response = $this->deleteJson("/api/mods/{$version->mod_id}/versions/{$version->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Version::all());
    }

    /** @test **/
    public function destroying_a_version_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $version = factory(Version::class)->create();

        $response = $this->deleteJson("/api/mods/{$version->mod_id}/versions/{$version->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Version::all());
    }

    /** @test */
    public function destroying_an_invalid_version_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/mods/99/versions/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function destroying_a_version_removes_related_dependencies()
    {
        $version = factory(Version::class)->create();
        $version->dependencies()->save(factory(Dependency::class)->make(['build_id' => $version->id]));

        $response = $this->deleteJson("/api/mods/{$version->mod_id}/versions/{$version->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Dependency::all());
    }
}
