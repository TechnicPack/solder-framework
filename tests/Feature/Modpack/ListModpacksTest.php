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

class ListModpacksTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function modpacks_can_be_listed()
    {
        $modpackA = factory(Modpack::class)->create();
        $modpackB = factory(Modpack::class)->create();

        $response = $this->getJson('/api/modpacks');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'name' => $modpackA->name,
            'slug' => $modpackA->slug,
        ]);
        $response->assertJsonFragment([
            'name' => $modpackB->name,
            'slug' => $modpackB->slug,
        ]);
    }

    /** @test **/
    public function listing_modpacks_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson('/api/modpacks');

        $response->assertStatus(401);
    }
}
