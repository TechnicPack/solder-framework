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

class StoreModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_stored()
    {
        $response = $this->postJson('/api/modpacks', [
            'name' => 'Test Modpack',
            'slug' => 'test-modpack',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Test Modpack']);
        $this->assertCount(1, Modpack::all());
    }

    /** @test **/
    public function storing_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/modpacks', [
            'name' => 'Test Modpack',
            'slug' => 'test-modpack',
        ]);

        $response->assertStatus(401);
        $this->assertCount(0, Modpack::all());
    }

    /** @test */
    public function the_name_field_is_required_to_store_a_modpack()
    {
        $response = $this->postJson('/api/modpacks', [
            'name' => '',
            'slug' => 'test-modpack',
        ]);

        $response->assertStatus(422);
        $this->assertSame(0, Modpack::count());
    }

    /** @test */
    public function the_slug_field_is_required_to_store_a_modpack()
    {
        $response = $this->postJson('/api/modpacks', [
            'name' => 'Test Modpack',
            'slug' => '',
        ]);

        $response->assertStatus(422);
        $this->assertSame(0, Modpack::count());
    }

    /** @test */
    public function the_slug_field_must_be_unique_to_store_a_modpack()
    {
        factory(Modpack::class)->create(['slug' => 'existing-modpack']);

        $response = $this->postJson('/api/modpacks', [
            'name' => 'Original Modpack',
            'slug' => 'existing-modpack',
        ]);

        $response->assertStatus(422);
        $this->assertSame(1, Modpack::count());
    }
}
