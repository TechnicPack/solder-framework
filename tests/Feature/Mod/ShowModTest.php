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
    public function it_shows_a_mod()
    {
        $mod = factory(Mod::class)->create([
            'name'        => 'Example Mod',
            'modid'       => 'example-mod',
            'author'      => 'John Doe',
            'url'         => 'http://google.com',
            'description' => 'Mod description.',
        ]);

        $response = $this->getJson("/api/mods/{$mod->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name'        => 'Example Mod',
            'modid'       => 'example-mod',
            'author'      => 'John Doe',
            'url'         => 'http://google.com',
            'description' => 'Mod description.', ]);
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $mod = factory(Mod::class)->create();

        $response = $this->getJson("/api/mods/{$mod->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function it_drops_invalid_requests()
    {
        $response = $this->getJson('/api/mods/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function it_can_include_versions()
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
