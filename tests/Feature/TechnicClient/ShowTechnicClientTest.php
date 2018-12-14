<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\TechnicClient;

use TechnicPack\SolderFramework\TechnicClient;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ShowTechnicClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_shows_a_client()
    {
        $client = factory(TechnicClient::class)->create();

        $response = $this->getJson("/api/technic-clients/{$client->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment($client->toArray());
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $client = factory(TechnicClient::class)->create();

        $response = $this->getJson("/api/technic-clients/{$client->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function it_drops_invalid_requests()
    {
        $response = $this->getJson('/api/technic-clients/99');

        $response->assertStatus(404);
    }
}
