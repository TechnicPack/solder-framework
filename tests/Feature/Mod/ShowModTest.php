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
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ShowModTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_mod_can_be_shown()
    {
        $mod = factory(Mod::class)->create([
            'name'  => 'Example Mod',
            'modid' => 'example-mod',
        ]);

        $response = $this->getJson("/api/mods/{$mod->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name'  => 'Example Mod',
            'modid' => 'example-mod',
        ]);
    }

    /** @test **/
    public function showing_a_mod_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $mod = factory(Mod::class)->create();

        $response = $this->getJson("/api/mods/{$mod->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function showing_an_invalid_mod_returns_a_404_error()
    {
        $response = $this->getJson('/api/mods/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function the_child_version_data_can_be_included()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->saveMany([
            factory(Version::class)->make(['tag' => '1.0.0a']),
            factory(Version::class)->make(['tag' => '1.0.0b']),
        ]);

        $response = $this->getJson("/api/mods/{$mod->id}?include=versions");

        $response->assertStatus(200);
        $response->assertJsonStructure(['versions']);
        $response->assertJsonCount(2, 'versions');
    }
}
