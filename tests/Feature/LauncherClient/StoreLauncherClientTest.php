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

class StoreLauncherClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_stores_a_client()
    {
        $response = $this->postJson('/api/launcher-clients', [
            'name'        => 'Test client',
            'token'       => 'test-client',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, LauncherClient::all());
        $response->assertJsonFragment(LauncherClient::first()->toArray());
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/launcher-clients', $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, LauncherClient::all());
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->postJson('/api/launcher-clients', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $this->assertSame(0, LauncherClient::count());
    }

    /** @test */
    public function token_is_required()
    {
        $response = $this->postJson('/api/launcher-clients', $this->validParams([
            'token' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('token');
        $this->assertSame(0, LauncherClient::count());
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
            'name'  => 'Test client',
            'token' => 'test-client',
        ], $overrides);
    }
}
