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

class DestroyTechnicClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_client_can_be_destroyed()
    {
        $client = factory(TechnicClient::class)->create();

        $response = $this->deleteJson("/api/technic-clients/{$client->id}");

        $response->assertStatus(204);
        $this->assertCount(0, TechnicClient::all());
    }

    /** @test **/
    public function destroying_a_client_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $client = factory(TechnicClient::class)->create();

        $response = $this->deleteJson("/api/technic-clients/{$client->id}");

        $response->assertStatus(401);
        $this->assertCount(1, TechnicClient::all());
    }

    /** @test */
    public function destroying_an_invalid_client_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/technic-clients/99');

        $response->assertStatus(404);
    }
}
