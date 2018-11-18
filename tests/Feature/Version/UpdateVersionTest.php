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

class UpdateVersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_version_can_be_updated()
    {
        $version = factory(Version::class)->create([
            'tag' => '1.0.0',
        ]);

        $response = $this->putJson("/api/mods/{$version->mod_id}/versions/{$version->id}", [
            'tag' => '2.0.0',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id'  => $version->id,
            'tag' => '2.0.0',
        ]);
    }

    /** @test **/
    public function updating_a_version_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $version = factory(Version::class)->create($this->originalParams());

        $response = $this->putJson("/api/mods/{$version->mod_id}/versions/{$version->id}", $this->validParams());

        $response->assertStatus(401);
    }

    /** @test */
    public function the_tag_is_required_to_update_a_version()
    {
        $version = factory(Version::class)->create();

        $response = $this->putJson("/api/mods/{$version->mod_id}/versions/{$version->id}", $this->validParams([
            'tag' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tag');
    }

    /** @test */
    public function the_tag_must_be_unique_for_a_given_mod()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->saveMany([
            $versionA = factory(Version::class)->create(['tag' => 'existing']),
            $versionB = factory(Version::class)->create(['tag' => 'my-tag']),
        ]);

        $response = $this->putJson("/api/mods/{$mod->id}/versions/{$versionB->id}", $this->validParams([
            'tag' => 'existing',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tag');
    }

    /**
     * Return a set of valid version creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function originalParams($overrides = [])
    {
        return array_merge([
            'tag' => '1.0.0',
        ], $overrides);
    }

    /**
     * Return a set of valid version creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'tag' => '2.0.0',
        ], $overrides);
    }
}
