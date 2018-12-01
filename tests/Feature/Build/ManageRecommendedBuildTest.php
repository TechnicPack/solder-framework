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
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ManageRecommendedBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_recommended_build_can_be_set()
    {
        $build = factory(Build::class)->create();
        $modpack = $build->modpack;

        $this->assertNull($modpack->recommendedBuild);

        $response = $this->putJson("/api/modpacks/{$modpack->id}/builds/recommended", [
            'build_id' => $build->id,
        ]);

        $response->assertStatus(201);
        tap($modpack->fresh()->recommended, function ($recommended) use ($build) {
            $this->assertNotNull($recommended);
            $this->assertTrue($recommended->is($build));
        });
    }

    /** @test **/
    public function setting_modpack_recommended_build_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $build = factory(Build::class)->create();
        $modpack = $build->modpack;

        $this->assertNull($modpack->recommendedBuild);

        $response = $this->putJson("/api/modpacks/{$modpack->id}/builds/recommended", [
            'build_id' => $build->id,
        ]);

        $response->assertStatus(401);
        $this->assertNull($modpack->fresh()->recommendedBuild);
    }

    /** @test */
    public function build_id_is_required()
    {
        $build = factory(Build::class)->create();
        $modpack = $build->modpack;

        $response = $this->putJson("/api/modpacks/{$modpack->id}/builds/recommended", [
            'build_id' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('build_id');
        $this->assertNull($modpack->fresh()->recommendedBuild);
    }

    /** @test */
    public function build_id_must_be_child_of_modpack()
    {
        $build = factory(Build::class)->create();
        $modpack = factory(Modpack::class)->create();

        $response = $this->putJson("/api/modpacks/{$modpack->id}/builds/recommended", [
            'build_id' => $build->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('build_id');
        $this->assertNull($modpack->fresh()->recommendedBuild);
    }
}
