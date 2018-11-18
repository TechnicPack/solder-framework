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

class DestroyModTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_mod_can_be_destroyed()
    {
        $mod = factory(Mod::class)->create();

        $response = $this->deleteJson("/api/mods/{$mod->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Mod::all());
    }

    /** @test **/
    public function destroying_a_mod_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $mod = factory(Mod::class)->create();

        $response = $this->deleteJson("/api/mods/{$mod->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Mod::all());
    }

    /** @test */
    public function destroying_an_invalid_mod_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/mod/99');

        $response->assertStatus(404);
    }
}
