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

use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\LauncherClient;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class AuthorizeClientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function clients_can_be_added_to_the_authorized_list()
    {
        $modpack = factory(Modpack::class)->create();
        $clientA = factory(LauncherClient::class)->create();
        $clientB = factory(LauncherClient::class)->create();

        $response = $this->postJson('/api/authorized-clients', [
            'modpack_id' => $modpack->id,
            'clients'    => [
                $clientA->id,
                $clientB->id,
            ],
        ]);

        $response->assertStatus(200);
        tap($modpack->clients, function ($clients) use ($clientB, $clientA) {
            $this->assertCount(2, $clients);
            $this->assertTrue($clients->contains($clientA));
            $this->assertTrue($clients->contains($clientB));
        });
    }

    /** @test **/
    public function authorized_clients_can_be_removed_from_modpack()
    {
        $modpack = factory(Modpack::class)->create();
        $clientA = factory(LauncherClient::class)->create();
        $clientB = factory(LauncherClient::class)->create();
        $modpack->clients()->sync($clientA, $clientB);

        $response = $this->postJson('/api/authorized-clients', [
            'modpack_id' => $modpack->id,
            'clients'    => [],
        ]);

        $response->assertStatus(200);
        tap($modpack->clients, function ($clients) use ($clientB, $clientA) {
            $this->assertCount(0, $clients);
            $this->assertFalse($clients->contains($clientA));
            $this->assertFalse($clients->contains($clientB));
        });
    }

    /** @test **/
    public function syncing_clients_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/authorized-clients', $this->validParams());

        $response->assertStatus(401);
    }

    /** @test */
    public function modpack_id_is_required()
    {
        $response = $this->postJson('/api/authorized-clients', $this->validParams([
            'modpack_id' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modpack_id');
    }

    /** @test */
    public function modpack_id_must_exist()
    {
        $response = $this->postJson('/api/authorized-clients', $this->validParams([
            'modpack_id' => '99',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modpack_id');
    }

    /** @test */
    public function clients_must_be_an_array()
    {
        $response = $this->postJson('/api/authorized-clients', $this->validParams([
            'clients' => 'string-value',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('clients');
    }

    /** @test */
    public function client_id_must_exist()
    {
        $response = $this->postJson('/api/authorized-clients', $this->validParams([
            'clients' => [
                99,
            ],
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('clients.0');
    }

    private function validParams($overrides = [])
    {
        $modpack = factory(Modpack::class)->create();
        $client = factory(LauncherClient::class)->create();

        return array_merge([
            'modpack_id' => $modpack->id,
            'clients'    => [
                $client->id,
            ],
        ], $overrides);
    }
}
