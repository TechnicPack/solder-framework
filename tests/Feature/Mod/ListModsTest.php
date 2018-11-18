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
use TechnicPack\SolderFramework\Version;

class ListModsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function mods_can_be_listed()
    {
        $modA = factory(Mod::class)->create();
        $modB = factory(Mod::class)->create();

        $response = $this->getJson('/api/mods');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'name'  => $modA->name,
            'modid' => $modA->modid,
        ]);
        $response->assertJsonFragment([
            'name'  => $modB->name,
            'modid' => $modB->modid,
        ]);
    }

    /** @test **/
    public function listing_mods_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson('/api/mods');

        $response->assertStatus(401);
    }

    /** @test **/
    public function the_versions_related_to_a_mod_can_be_included()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->saveMany([
            factory(Version::class)->make(['tag' => '1.0.0a']),
            factory(Version::class)->make(['tag' => '1.0.0b']),
        ]);

        $response = $this->getJson('/api/mods?include=versions');

        $response->assertStatus(200);
        $response->assertJsonStructure([['versions']]);
    }
}
