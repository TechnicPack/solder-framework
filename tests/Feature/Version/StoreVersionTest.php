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

use Illuminate\Http\UploadedFile;
use TechnicPack\SolderFramework\Mod;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class StoreVersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_version_can_be_stored()
    {
        Storage::fake();
        $mod = factory(Mod::class)->create();

        $response = $this->postJson("/api/mods/{$mod->id}/versions", [
            'tag'     => '1.0.0',
            'package' => UploadedFile::fake()->create('example-1.0.0.zip', 256),
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Version::all());
        $response->assertJson([
            'tag'          => '1.0.0',
            'package_name' => 'example-1.0.0.zip',
            'package_size' => 256 * 1024,
            'package_hash' => 'd41d8cd98f00b204e9800998ecf8427e',
        ]);
    }

    /** @test **/
    public function storing_a_version_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $mod = factory(Mod::class)->create();

        $response = $this->postJson("/api/mods/{$mod->id}/versions", $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, Version::all());
    }

    /** @test */
    public function the_tag_is_required_to_store_a_version()
    {
        $mod = factory(Mod::class)->create();

        $response = $this->postJson("/api/mods/{$mod->id}/versions", $this->validParams([
            'tag' => '',
        ]));

        $response->assertJsonValidationErrors('tag');
        $this->assertSame(0, Version::count());
    }

    /** @test */
    public function the_tag_must_be_unique_for_a_given_mod()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->save(factory(Version::class)->create(['tag' => 'existing']));

        $response = $this->postJson("/api/mods/{$mod->id}/versions", $this->validParams([
            'tag' => 'existing',
        ]));

        $response->assertJsonValidationErrors('tag');
        $this->assertSame(1, Version::count());
    }

    /** @test */
    public function the_package_is_required_to_store_a_version()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->save(factory(Version::class)->create(['tag' => 'existing']));

        $response = $this->postJson("/api/mods/{$mod->id}/versions", $this->validParams([
            'package' => null,
        ]));

        $response->assertJsonValidationErrors('package');
        $this->assertSame(1, Version::count());
    }

    /** @test */
    public function the_package_must_be_a_zip_file()
    {
        $mod = factory(Mod::class)->create();
        $mod->versions()->save(factory(Version::class)->create(['tag' => 'existing']));

        $response = $this->postJson("/api/mods/{$mod->id}/versions", $this->validParams([
            'package' => UploadedFile::fake()->create('example-1.0.0.jar'),
        ]));

        $response->assertJsonValidationErrors('package');
        $this->assertSame(1, Version::count());
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
            'tag'     => '1.0.0',
            'package' => UploadedFile::fake()->create('example-1.0.0.zip', 256),
        ], $overrides);
    }
}
