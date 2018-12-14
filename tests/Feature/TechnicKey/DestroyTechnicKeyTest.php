<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\TechnicKey;

use TechnicPack\SolderFramework\TechnicKey;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyTechnicKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_key_can_be_destroyed()
    {
        $this->withoutExceptionHandling();
        $key = factory(TechnicKey::class)->create();

        $response = $this->deleteJson("/api/technic-keys/{$key->id}");

        $response->assertStatus(204);
        $this->assertCount(0, TechnicKey::all());
    }

    /** @test **/
    public function destroying_a_key_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $key = factory(TechnicKey::class)->create();

        $response = $this->deleteJson("/api/technic-keys/{$key->id}");

        $response->assertStatus(401);
        $this->assertCount(1, TechnicKey::all());
    }

    /** @test */
    public function destroying_an_invalid_key_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/key/99');

        $response->assertStatus(404);
    }
}
