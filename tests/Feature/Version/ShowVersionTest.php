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

use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ShowVersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function an_individual_version_can_be_shown()
    {
        $version = factory(Version::class)->create([
            'tag' => '1.0.0',
        ]);

        $response = $this->getJson("/api/mods/{$version->mod_id}/versions/{$version->id}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'id'          => $version->id,
            'tag'         => '1.0.0',
            'created_at'  => $version->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $version->updated_at->format('Y-m-d H:i:s'),
        ]);
    }

    /** @test **/
    public function showing_a_version_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $version = factory(Version::class)->create();

        $response = $this->getJson("/api/mods/{$version->mod_id}/versions/{$version->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function showing_an_invalid_version_returns_a_404_error()
    {
        $response = $this->getJson('/api/mods/99/versions/99');

        $response->assertStatus(404);
    }
}
