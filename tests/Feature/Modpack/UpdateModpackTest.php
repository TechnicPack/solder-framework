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

use TechnicPack\SolderFramework\Models\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class UpdateModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_updated()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
            'slug' => 'existing-modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => 'Revised Modpack',
            'slug' => 'revised-modpack',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Revised Modpack']);
        $this->assertCount(1, Modpack::all());
        $this->assertSame('Revised Modpack', $modpack->fresh()->name);
    }

    /** @test **/
    public function updating_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
            'slug' => 'existing-modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => 'Revised Modpack',
            'slug' => 'revised-modpack',
        ]);

        $response->assertStatus(401);
        $this->assertSame('Existing Modpack', $modpack->fresh()->name);
        $this->assertSame('existing-modpack', $modpack->fresh()->slug);
    }

    /** @test */
    public function updating_an_invalid_modpack_returns_a_404_error()
    {
        $response = $this->putJson('/api/modpacks/99', [
            'name' => 'Revised Modpack',
            'slug' => 'revised-modpack',
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function the_name_field_is_required_to_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
            'slug' => 'existing-modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => '',
            'slug' => 'test-modpack',
        ]);

        $response->assertStatus(422);
        $this->assertSame('Existing Modpack', $modpack->fresh()->name);
    }

    /** @test */
    public function the_slug_field_is_required_to_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
            'slug' => 'existing-modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => 'Test Modpack',
            'slug' => '',
        ]);

        $response->assertStatus(422);
        $this->assertSame('existing-modpack', $modpack->fresh()->slug);
    }

    /** @test */
    public function the_slug_field_must_be_unique_to_update_a_modpack()
    {
        $modpackA = factory(Modpack::class)->create(['slug' => 'existing-modpack']);
        $modpackB = factory(Modpack::class)->create([
            'name' => 'Original Modpack',
            'slug' => 'original-modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpackB->id}", [
            'name' => 'Original Modpack',
            'slug' => 'existing-modpack',
        ]);

        $response->assertStatus(422);
        $this->assertSame('existing-modpack', $modpackA->fresh()->slug);
        $this->assertSame('original-modpack', $modpackB->fresh()->slug);
    }

    /** @test */
    public function the_original_slug_field_may_be_resubmitted_when_updating_a_modpack()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Original Modpack',
            'slug' => 'original-modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => 'Original Modpack',
            'slug' => 'original-modpack',
        ]);

        $response->assertStatus(200);
        $this->assertSame('original-modpack', $modpack->fresh()->slug);
    }
}
