<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\LauncherClient;

use TechnicPack\SolderFramework\LauncherClient;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyLauncherClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_client_can_be_destroyed()
    {
        $client = factory(LauncherClient::class)->create();

        $response = $this->deleteJson("/api/launcher-clients/{$client->id}");

        $response->assertStatus(204);
        $this->assertCount(0, LauncherClient::all());
    }

    /** @test **/
    public function destroying_a_client_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $client = factory(LauncherClient::class)->create();

        $response = $this->deleteJson("/api/launcher-clients/{$client->id}");

        $response->assertStatus(401);
        $this->assertCount(1, LauncherClient::all());
    }

    /** @test */
    public function destroying_an_invalid_client_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/client/99');

        $response->assertStatus(404);
    }
}
