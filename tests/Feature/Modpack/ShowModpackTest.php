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

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ShowModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_shown()
    {
        $modpack = factory(Modpack::class)->states('with-icon')->create([
            'name' => 'Example Modpack',
            'slug' => 'example-modpack',
            'url'  => 'http://example.com/example-mod',
        ]);

        $response = $this->getJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'id'         => $modpack->id,
            'name'       => 'Example Modpack',
            'slug'       => 'example-modpack',
            'icon'       => $modpack->icon,
            'url'        => 'http://example.com/example-mod',
            'created_at' => $modpack->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $modpack->updated_at->format('Y-m-d H:i:s'),
        ]);
    }

    /** @test **/
    public function showing_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create();

        $response = $this->getJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function showing_an_invalid_modpack_returns_a_404_error()
    {
        $response = $this->getJson('/api/modpacks/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function the_child_build_data_can_be_included()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->saveMany([
            factory(Build::class)->make(['tag' => '1.0.0a']),
            factory(Build::class)->make(['tag' => '1.0.0b']),
        ]);

        $response = $this->getJson("/api/modpacks/{$modpack->id}?include=builds");

        $response->assertStatus(200);
        $response->assertJsonStructure(['builds']);
        $response->assertJsonCount(2, 'builds');
    }

    /** @test **/
    public function the_latest_build_data_can_be_included()
    {
        $this->withoutExceptionHandling();
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->saveMany([
            $buildA = factory(Build::class)->make(['tag' => '1.0.0a']),
            $buildB = factory(Build::class)->make(['tag' => '1.0.0b']),
        ]);
        $modpack->latest()->associate($buildB);
        $modpack->save();

        $response = $this->getJson("/api/modpacks/{$modpack->id}?include=latest");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $buildB->id,
        ]);
    }

    /** @test **/
    public function the_recommended_build_data_can_be_included()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->saveMany([
            $buildA = factory(Build::class)->make(['tag' => '1.0.0a']),
            $buildB = factory(Build::class)->make(['tag' => '1.0.0b']),
        ]);
        $modpack->recommended()->associate($buildA);
        $modpack->save();

        $response = $this->getJson("/api/modpacks/{$modpack->id}?include=recommended");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $buildA->id,
        ]);
    }
}
