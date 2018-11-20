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
    public function update_a_mod()
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
    public function drop_unauthenticated_requests()
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
    public function drop_requests_for_invalid_mod()
    {
        $response = $this->putJson('/api/mods/99', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function name_is_required()
    {
        $mod = factory(Mod::class)->create();

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function modid_is_required()
    {
        $mod = factory(Mod::class)->create();

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
    }

    /** @test */
    public function modid_is_unique()
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
    public function current_modid_may_be_resubmitted()
    {
        $mod = factory(Mod::class)->create(['modid' => 'existing-mod']);

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => 'existing-mod',
        ]));

        $response->assertStatus(200);
    }

    /** @test */
    public function modid_cannot_contain_spaces()
    {
        $mod = factory(Mod::class)->create($this->originalParams());

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => 'a a',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertArraySubset($this->originalParams(), $mod->fresh()->getAttributes());
    }

    /** @test */
    public function modid_cannot_contain_capitals()
    {
        $mod = factory(Mod::class)->create($this->originalParams());

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => 'AA',
        ]));
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertArraySubset($this->originalParams(), $mod->fresh()->getAttributes());
    }

    /** @test */
    public function modid_cannot_exceed_64_characters()
    {
        $mod = factory(Mod::class)->create($this->originalParams());

        $response = $this->putJson("/api/mods/{$mod->id}", $this->validParams([
            'modid' => str_repeat('a', 65),
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertArraySubset($this->originalParams(), $mod->fresh()->getAttributes());
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
