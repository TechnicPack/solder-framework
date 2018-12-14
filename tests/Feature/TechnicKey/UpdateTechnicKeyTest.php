<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\TechnicKey;

use TechnicPack\SolderFramework\TechnicKey;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class UpdateTechnicKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function update_a_key()
    {
        $key = factory(TechnicKey::class)->create([
            'name'        => 'Existing Key',
            'token'       => 'existing-key',
        ]);

        $response = $this->putJson("/api/technic-keys/{$key->id}", [
            'name'        => 'Revised Key',
            'token'       => 'revised-key',
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, TechnicKey::all());
        $response->assertExactJson($key->fresh()->toArray());
    }

    /** @test **/
    public function drop_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $key = factory(TechnicKey::class)->create();

        $response = $this->putJson("/api/technic-keys/{$key->id}", $this->validParams());

        $response->assertStatus(401);
    }

    /** @test */
    public function drop_invalid_requests()
    {
        $response = $this->putJson('/api/technic-keys/99', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function name_is_required()
    {
        $key = factory(TechnicKey::class)->create();

        $response = $this->putJson("/api/technic-keys/{$key->id}", $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function token_is_required()
    {
        $key = factory(TechnicKey::class)->create();

        $response = $this->putJson("/api/technic-keys/{$key->id}", $this->validParams([
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
            'name'  => 'Existing Key',
            'token' => 'existing-key',
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
            'name'  => 'Revised Key',
            'token' => 'revised-key',
        ], $overrides);
    }
}
