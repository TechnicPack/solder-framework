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

class ListLauncherClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_lists_clients()
    {
        $clientA = factory(LauncherClient::class)->create();
        $clientB = factory(LauncherClient::class)->create();

        $response = $this->getJson('/api/launcher-clients');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertExactJson([
            $clientA->toArray(),
            $clientB->toArray(),
        ]);
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson('/api/launcher-clients');

        $response->assertStatus(401);
    }
}
