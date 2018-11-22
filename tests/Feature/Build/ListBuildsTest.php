<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Build;

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ListBuildsTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function builds_can_be_listed()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->saveMany([
            $buildA = factory(Build::class)->make(),
            $buildB = factory(Build::class)->make(),
        ]);

        $response = $this->getJson("/api/modpacks/{$modpack->id}/builds");

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'id'  => $buildA->id,
            'tag' => $buildA->tag,
        ]);
        $response->assertJsonFragment([
            'id'  => $buildB->id,
            'tag' => $buildB->tag,
        ]);
    }

    /** @test **/
    public function listing_builds_requires_authentication()
    {
        $modpack = factory(Modpack::class)->create();

        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson("/api/modpacks/{$modpack->id}/builds");

        $response->assertStatus(401);
    }
}
