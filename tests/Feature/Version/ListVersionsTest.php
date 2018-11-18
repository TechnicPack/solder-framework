<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Version;

use TechnicPack\SolderFramework\Mod;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ListVersionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function versions_can_be_listed()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->saveMany([
            $versionA = factory(Version::class)->make(),
            $versionB = factory(Version::class)->make(),
        ]);

        $response = $this->getJson("/api/mods/{$mod->id}/versions");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'id'  => $versionA->id,
            'tag' => $versionA->tag,
        ]);
        $response->assertJsonFragment([
            'id'  => $versionB->id,
            'tag' => $versionB->tag,
        ]);
    }

    /** @test **/
    public function listing_versions_requires_authentication()
    {
        $mod = factory(Mod::class)->create();

        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson("/api/mods/{$mod->id}/versions");

        $response->assertStatus(401);
    }
}
