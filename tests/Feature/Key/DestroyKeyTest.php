<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Key;

use TechnicPack\SolderFramework\Key;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_key_can_be_destroyed()
    {
        $key = factory(Key::class)->create();

        $response = $this->deleteJson("/api/keys/{$key->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Key::all());
    }

    /** @test **/
    public function destroying_a_key_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $key = factory(Key::class)->create();

        $response = $this->deleteJson("/api/keys/{$key->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Key::all());
    }

    /** @test */
    public function destroying_an_invalid_key_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/key/99');

        $response->assertStatus(404);
    }
}
