<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\PlatformKey;

use TechnicPack\SolderFramework\PlatformKey;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class StorePlatformKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_stores_a_key()
    {
        $response = $this->postJson('/api/platform-keys', [
            'name'        => 'Test Key',
            'token'       => 'test-key',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, PlatformKey::all());
        $response->assertJsonFragment(PlatformKey::first()->toArray());
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/platform-keys', $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, PlatformKey::all());
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->postJson('/api/platform-keys', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $this->assertSame(0, PlatformKey::count());
    }

    /** @test */
    public function token_is_required()
    {
        $response = $this->postJson('/api/platform-keys', $this->validParams([
            'token' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('token');
        $this->assertSame(0, PlatformKey::count());
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
            'name'  => 'Test Key',
            'token' => 'test-key',
        ], $overrides);
    }
}
