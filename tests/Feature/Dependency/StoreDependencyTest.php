<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Dependency;

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class StoreDependencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_stores_a_dependency()
    {
        $build = factory(Build::class)->create();
        $version = factory(Version::class)->create();

        $response = $this->postJson('/api/dependencies', [
            'build_id'   => $build->id,
            'version_id' => $version->id,
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Dependency::all());
        tap(Dependency::first(), function ($dependency) use ($build, $version, $response) {
            $this->assertTrue($dependency->build->is($build));
            $this->assertTrue($dependency->version->is($version));

            $response->assertExactJson([
                'id'         => $dependency->id,
                'created_at' => $dependency->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $dependency->updated_at->format('Y-m-d H:i:s'),
            ]);
        });
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/dependencies', $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, Dependency::all());
    }

    /** @test */
    public function build_id_is_required()
    {
        $response = $this->postJson('/api/dependencies', $this->validParams([
            'build_id' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('build_id');
        $this->assertSame(0, Dependency::count());
    }

    /** @test */
    public function build_id_exists()
    {
        $response = $this->postJson('/api/dependencies', $this->validParams([
            'build_id' => 99,
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('build_id');
        $this->assertSame(0, Dependency::count());
    }

    /** @test */
    public function version_id_is_required()
    {
        $response = $this->postJson('/api/dependencies', $this->validParams([
            'version_id' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('version_id');
        $this->assertSame(0, Dependency::count());
    }

    /** @test */
    public function only_allow_one_version_of_a_mod_per_build()
    {
        $dependency = factory(Dependency::class)->create();
        $mod = $dependency->mod;
        $build = $dependency->build;
        $versionA = $dependency->version;
        $versionB = factory(Version::class)->create(['mod_id' => $mod->id]);

        $response = $this->postJson('/api/dependencies', [
            'build_id'   => $build->id,
            'version_id' => $versionB->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('version_id');
        $this->assertSame(1, Dependency::count());
    }

    /** @test */
    public function version_id_exists()
    {
        $response = $this->postJson('/api/dependencies', $this->validParams([
            'version_id' => 99,
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('version_id');
        $this->assertSame(0, Dependency::count());
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
        $build = factory(Build::class)->create();
        $version = factory(Version::class)->create();

        return array_merge([
            'build_id'   => $build->id,
            'version_id' => $version->id,
        ], $overrides);
    }
}
