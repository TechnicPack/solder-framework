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
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ManageBuildVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function update_build_visibility()
    {
        $build = factory(Build::class)->state('hidden')->create();

        $response = $this->putJson("/api/builds/{$build->id}/visibility", [
            'visibility' => 'public',
        ]);

        $response->assertStatus(200);
        tap($build->fresh(), function ($build) use ($response) {
            $response->assertJson($build->jsonSerialize());
            $this->assertSame('public', $build->visibility);
        });
    }

    /** @test **/
    public function setting_modpack_latest_build_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $build = factory(Build::class)->state('hidden')->create();

        $response = $this->putJson("/api/builds/{$build->id}/visibility", [
            'visibility' => 'public',
        ]);

        $response->assertStatus(401);
        $this->assertSame('hidden', $build->fresh()->visibility);
    }

    /** @test */
    public function visibility_is_required()
    {
        $build = factory(Build::class)->state('hidden')->create();

        $response = $this->putJson("/api/builds/{$build->id}/visibility", [
            'visibility' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('visibility');
        $this->assertSame('hidden', $build->fresh()->visibility);
    }

    /** @test */
    public function visibility_is_in_list()
    {
        $build = factory(Build::class)->state('hidden')->create();

        $response = $this->putJson("/api/builds/{$build->id}/visibility", [
            'visibility' => 'ascended',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('visibility');
        $this->assertSame('hidden', $build->fresh()->visibility);
    }
}
