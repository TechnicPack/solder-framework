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

class ShowKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_shows_a_key()
    {
        $key = factory(Key::class)->create();

        $response = $this->getJson("/api/keys/{$key->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment($key->toArray());
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $key = factory(Key::class)->create();

        $response = $this->getJson("/api/keys/{$key->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function it_drops_invalid_requests()
    {
        $response = $this->getJson('/api/keys/99');

        $response->assertStatus(404);
    }
}
