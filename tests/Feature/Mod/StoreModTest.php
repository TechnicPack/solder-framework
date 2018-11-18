<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Mod;

use TechnicPack\SolderFramework\Mod;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class StoreModTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_mod_can_be_stored()
    {
        $response = $this->postJson('/api/mods', [
            'name'  => 'Test Mod',
            'modid' => 'test-mod',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Mod::all());
        $response->assertJsonFragment([
            'name'  => 'Test Mod',
            'modid' => 'test-mod',
        ]);
    }

    /** @test **/
    public function storing_a_mod_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/mods', $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, Mod::all());
    }

    /** @test */
    public function the_name_field_is_required_to_store_a_mod()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function the_modid_field_is_required_to_store_a_mod()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function the_modid_field_must_be_unique_to_store_a_mod()
    {
        factory(Mod::class)->create(['modid' => 'existing-mod']);

        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => 'existing-mod',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(1, Mod::count());
    }

    /** @test */
    public function the_modid_field_must_not_contain_spaces_or_special_characters()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => 'spaces and symbols!',
        ]));

        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
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
            'name'  => 'Test Mod',
            'modid' => 'test-mod',
        ], $overrides);
    }
}
