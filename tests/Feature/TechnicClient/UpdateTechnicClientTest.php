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

class UpdateTechnicClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function update_a_client()
    {
        $client = factory(TechnicClient::class)->create([
            'name'        => 'Existing client',
            'token'       => 'existing-client',
        ]);

        $response = $this->putJson("/api/technic-clients/{$client->id}", [
            'name'        => 'Revised client',
            'token'       => 'revised-client',
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, TechnicClient::all());
        $response->assertExactJson($client->fresh()->toArray());
    }

    /** @test **/
    public function drop_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $client = factory(TechnicClient::class)->create();

        $response = $this->putJson("/api/technic-clients/{$client->id}", $this->validParams());

        $response->assertStatus(401);
    }

    /** @test */
    public function drop_invalid_requests()
    {
        $response = $this->putJson('/api/technic-clients/99', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function name_is_required()
    {
        $client = factory(TechnicClient::class)->create();

        $response = $this->putJson("/api/technic-clients/{$client->id}", $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function token_is_required()
    {
        $client = factory(TechnicClient::class)->create();

        $response = $this->putJson("/api/technic-clients/{$client->id}", $this->validParams([
            'token' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('token');
    }

    /**
     * Return a set of valid build creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function originalParams($overrides = [])
    {
        return array_merge([
            'name'  => 'Existing client',
            'token' => 'existing-client',
        ], $overrides);
    }

    /**
     * Return a set of valid build creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'name'  => 'Revised client',
            'token' => 'revised-client',
        ], $overrides);
    }
}
