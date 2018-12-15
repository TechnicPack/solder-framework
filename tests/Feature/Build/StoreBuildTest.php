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

class StoreBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_build_can_be_stored()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/builds", [
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Build::all());
        $response->assertJson([
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
            'visibility'        => 'hidden',
        ]);
    }

    /** @test **/
    public function storing_a_build_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/builds", $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, Build::all());
    }

    /** @test */
    public function the_tag_is_required_to_store_a_build()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/builds", $this->validParams([
            'tag' => '',
        ]));

        $response->assertJsonValidationErrors('tag');
        $this->assertSame(0, Build::count());
    }

    /** @test */
    public function the_tag_must_be_unique_for_a_given_modpack()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->save(factory(Build::class)->create(['tag' => 'existing']));

        $response = $this->postJson("/api/modpacks/{$modpack->id}/builds", $this->validParams([
            'tag' => 'existing',
        ]));

        $response->assertJsonValidationErrors('tag');
        $this->assertSame(1, Build::count());
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
            'tag'               => '1.0.0',
            'minecraft_version' => '1.7.10',
            'java_version'      => '1.8',
            'java_memory'       => '2048',
        ], $overrides);
    }
}
