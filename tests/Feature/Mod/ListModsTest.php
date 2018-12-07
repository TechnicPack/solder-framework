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
use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ListModsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_lists_mods()
    {
        $modA = factory(Mod::class)->create();
        $modB = factory(Mod::class)->create();

        $response = $this->getJson('/api/mods');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'name'        => $modA->name,
            'modid'       => $modA->modid,
            'author'      => $modA->author,
            'url'         => $modA->url,
            'description' => $modA->description,
        ]);
        $response->assertJsonFragment([
            'name'        => $modB->name,
            'modid'       => $modB->modid,
            'author'      => $modB->author,
            'url'         => $modB->url,
            'description' => $modB->description,
        ]);
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson('/api/mods');

        $response->assertStatus(401);
    }

    /** @test **/
    public function it_can_include_versions()
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

    /** @test **/
    public function mod_list_can_be_filtered_to_exclude_items_a_build_already_depends_on()
    {
        $version = factory(Version::class)->create();
        $modA = factory(Mod::class)->create();
        $modB = $version->mod;
        $build = factory(Build::class)->create();
        $build->dependencies()->create(['version_id' => $version->id]);

        $response = $this->getJson("/api/mods?filter[not_in_build]={$build->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $modA->id]);
        $response->assertJsonMissing(['id' => $modB->id]);
    }
}
