<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Dependency;

use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyDependencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_dependency_can_be_destroyed()
    {
        $dependency = factory(Dependency::class)->create();

        $response = $this->deleteJson("/api/dependencies/{$dependency->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Dependency::all());
    }

    /** @test **/
    public function destroying_a_dependency_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $dependency = factory(Dependency::class)->create();

        $response = $this->deleteJson("/api/dependencies/{$dependency->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Dependency::all());
    }

    /** @test */
    public function destroying_an_invalid_build_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/dependencies/99');

        $response->assertStatus(404);
    }
}
