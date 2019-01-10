<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Package;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class UploadPackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_file_can_be_uploaded()
    {
        $version = factory(Version::class)->create();
        $file = UploadedFile::fake()->create('ExampleMod-1.0.0.zip', 1);

        $response = $this->postJson("/api/mods/{$version->mod_id}/versions/{$version->id}/package", [
            'package' => $file,
        ]);

        $response->assertStatus(201);
        Storage::disk(config('solder.disk.files'))->assertExists($file->hashName('files'));

        tap($version->fresh(), function ($version) use ($response, $file) {
            $this->assertSame($file->hashName('files'), $version->package);
            $this->assertSame('ExampleMod-1.0.0.zip', $version->package_name);
            $this->assertSame(1024, $version->package_size);
            $this->assertSame(md5_file($file->path()), $version->package_hash);

            $response->assertJsonFragment([
                'package'      => $version->package,
                'package_url'  => Storage::url($version->package),
                'package_name' => $version->package_name,
                'package_size' => $version->package_size,
                'package_hash' => $version->package_hash,
            ]);
        });
    }

    /** @test **/
    public function storing_a_version_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $version = factory(Version::class)->create();
        $file = UploadedFile::fake()->create('ExampleMod-1.0.0.zip', 1);

        $response = $this->postJson("/api/mods/{$version->mod_id}/versions/{$version->id}/package", [
            'package' => $file,
        ]);

        $response->assertStatus(401);
        Storage::disk(config('solder.disk.files'))->assertMissing($file->hashName('files'));
    }

    /** @test */
    public function the_package_is_required()
    {
        $version = factory(Version::class)->create();

        $response = $this->postJson("/api/mods/{$version->mod_id}/versions/{$version->id}/package", [
            'package' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('package');
    }

    /** @test */
    public function the_package_is_a_zip_file()
    {
        $version = factory(Version::class)->create();
        $file = UploadedFile::fake()->create('Invalid File.jar', 1);

        $response = $this->postJson("/api/mods/{$version->mod_id}/versions/{$version->id}/package", [
            'package' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('package');
        Storage::disk(config('solder.disk.files'))->assertMissing($file->hashName('files'));
    }

    /** @test */
    public function the_package_can_be_deleted()
    {
        $version = factory(Version::class)->state('with-package')->create();

        Storage::disk(config('solder.disk.files'))->assertExists($version->package);

        $response = $this->deleteJson("/api/mods/{$version->mod_id}/versions/{$version->id}/package");

        $response->assertStatus(204);
        Storage::disk(config('solder.disk.files'))->assertMissing($version->package);

        tap($version->fresh(), function ($version) use ($response) {
            $this->assertNull($version->package);
            $this->assertNull($version->package_name);
            $this->assertNull($version->package_size);
            $this->assertNull($version->package_hash);
        });
    }
}
