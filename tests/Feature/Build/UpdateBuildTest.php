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

class UpdateBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_build_can_be_updated()
    {
        $build = factory(Build::class)->create([
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
        ]);

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", [
            'tag'               => '2.0.0',
            'minecraft_version' => '1.11.2',
            'java_version'      => '1.9',
            'java_memory'       => '4096',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id'                => $build->id,
            'tag'               => '2.0.0',
            'minecraft_version' => '1.11.2',
            'java_version'      => '1.9',
            'java_memory'       => '4096',
        ]);
    }

    /** @test **/
    public function updating_a_build_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $build = factory(Build::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams());

        $response->assertStatus(401);
    }

    /** @test */
    public function tag_is_required()
    {
        $build = factory(Build::class)->create();

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'tag' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tag');
    }

    /** @test */
    public function tag_is_unique()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->saveMany([
            $buildA = factory(Build::class)->create(['tag' => 'existing']),
            $buildB = factory(Build::class)->create(['tag' => 'my-tag']),
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}/builds/{$buildB->id}", $this->validParams([
            'tag' => 'existing',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tag');
    }

    /** @test */
    public function tag_is_url_safe()
    {
        $build = factory(Build::class)->create();

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'tag' => 'mod&tools',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tag');
    }

    /** @test */
    public function tag_can_be_resubmitted()
    {
        $build = factory(Build::class)->create($this->originalParams([
            'tag' => 'my-tag',
        ]));

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'tag' => 'my-tag',
        ]));

        $response->assertStatus(200);
        $response->assertJsonMissingValidationErrors('tag');
    }

    /** @test */
    public function minecraft_version_is_required()
    {
        $build = factory(Build::class)->create();

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'minecraft_version' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('minecraft_version');
    }

    /** @test */
    public function java_version_is_not_required()
    {
        $build = factory(Build::class)->create($this->originalParams([
            'java_version' => '1.8',
        ]));

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'java_version' => '',
        ]));

        $response->assertStatus(200);
        $response->assertJsonMissingValidationErrors('java_version');
        $this->assertNull($build->fresh()->java_version);
    }

    /** @test */
    public function java_memory_is_not_required()
    {
        $build = factory(Build::class)->create($this->originalParams([
            'java_memory' => '2048',
        ]));

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'java_memory' => '',
        ]));

        $response->assertStatus(200);
        $response->assertJsonMissingValidationErrors('java_memory');
        $this->assertNull($build->fresh()->java_memory);
    }

    /** @test */
    public function java_memory_is_integer()
    {
        $build = factory(Build::class)->create();

        $response = $this->putJson("/api/modpacks/{$build->modpack_id}/builds/{$build->id}", $this->validParams([
            'java_memory' => 'five',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('java_memory');
    }

    /**
     * Return a set of valid build creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function originalParams($overrides = [])
    {
        return array_merge([
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
        ], $overrides);
    }

    /**
     * Return a set of valid build creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'tag'               => '2.0.0',
            'minecraft_version' => '1.11.2',
            'java_version'      => '1.9',
            'java_memory'       => '4096',
        ], $overrides);
    }
}
