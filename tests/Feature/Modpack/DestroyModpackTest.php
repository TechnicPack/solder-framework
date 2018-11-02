<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Modpack;

use TechnicPack\SolderFramework\Models\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_destroyed()
    {
        $this->withoutExceptionHandling();
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
        ]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Modpack::all());
    }

    /** @test **/
    public function destroying_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create();

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Modpack::all());
    }

    /** @test **/
    public function destroying_a_modpack_is_rate_limited()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
        ]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $this->assertNotNull($response->headers->get('X-Ratelimit-Limit'));
    }

    /** @test */
    public function destroying_an_invalid_modpack_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/modpacks/99');

        $response->assertStatus(404);
    }
}
