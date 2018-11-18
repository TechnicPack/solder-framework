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

class UpdateModTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_mod_can_be_updated()
    {
        $mod = factory(Mod::class)->create([
            'name'  => 'Existing Mod',
            'modid' => 'existing-mod',
        ]);

        $response = $this->putJson("/api/mods/{$mod->id}", [
            'name'  => 'Revised Mod',
            'modid' => 'revised-mod',
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, Mod::all());
        $this->assertSame('Revised Mod', $mod->fresh()->name);
        $this->assertSame('revised-mod', $mod->fresh()->modid);
        $response->assertJsonFragment([
            'name'  => 'Revised Mod',
            'modid' => 'revised-mod',
        ]);
    }

    /** @test **/
    public function updating_a_mod_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $mod = factory(Mod::class)->create([
            'name'  => 'Existing Mod',
            'modid' => 'existing-mod',
        ]);

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams());

        $response->assertStatus(401);
        $this->assertSame('Existing Mod', $mod->fresh()->name);
        $this->assertSame('existing-mod', $mod->fresh()->modid);
    }

    /** @test */
    public function updating_an_invalid_mod_returns_a_404_error()
    {
        $response = $this->putJson('/api/mods/99', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function the_name_is_required_to_update_a_mod()
    {
        $mod = factory(Mod::class)->create();

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function the_modid_field_is_required_to_update_a_mod()
    {
        $mod = factory(Mod::class)->create();

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
    }

    /** @test */
    public function the_slug_field_must_be_unique_to_update_a_mod()
    {
        $modA = factory(Mod::class)->create(['modid' => 'existing-mod']);
        $modB = factory(Mod::class)->create();

        $response = $this->putJson("/api/mods/{$modB->id}", $this->validParams([
            'modid' => 'existing-mod',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
    }

    /** @test */
    public function the_original_modid_field_may_be_resubmitted_when_updating_a_mod()
    {
        $mod = factory(Mod::class)->create(['modid' => 'existing-mod']);

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => 'existing-mod',
        ]));

        $response->assertStatus(200);
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
            'name'  => 'Existing Mod',
            'modid' => 'existing-mod',
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
            'name'  => 'Revised Mod',
            'modid' => 'revised-mod',
        ], $overrides);
    }
}
