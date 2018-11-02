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

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Models\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class UploadModpackIconTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function an_icon_can_be_uploaded()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('modpack-icon.jpg', 500, 500);
        $modpack = factory(Modpack::class)->create();

        $response = $this->putJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(200);
        $this->assertSame('icons/'.$file->hashName(), $modpack->fresh()->icon);
        Storage::disk()->assertExists('icons/'.$file->hashName());
    }

    /** @test **/
    public function the_icon_field_is_required()
    {
        Storage::fake();

        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => null,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon);
    }

    /** @test **/
    public function the_icon_must_be_an_image()
    {
        Storage::fake();

        $file = UploadedFile::fake()->create('report.pdf');
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon);
    }

    /** @test **/
    public function the_icon_must_be_under_5000_kilobytes()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('modpack-icon.jpg')->size(5001);
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon);
    }

    /** @test **/
    public function the_icon_width_must_be_less_than_500_px()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('modpack-icon.jpg', 501, 500);
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon);
    }

    /** @test **/
    public function the_icon_height_must_be_less_than_500_px()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('modpack-icon.jpg', 500, 501);
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon);
    }

    /** @test **/
    public function an_icon_can_be_destroyed()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('modpack-icon.jpg');
        $modpack = factory(Modpack::class)->create(['icon' => $file->store('icons')]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}/icon");

        $response->assertStatus(204);
        $this->assertNull($modpack->fresh()->icon);
        Storage::disk()->assertMissing('icons/'.$file->hashName());
    }

    /** @test **/
    public function old_icons_are_removed_from_storage_when_replaced()
    {
        Storage::fake();

        $originalFile = UploadedFile::fake()->image('original-icon.jpg');
        $newFile = UploadedFile::fake()->image('new-icon.jpg');

        $modpack = factory(Modpack::class)->create([
            'icon' => $originalFile->store('icons'),
        ]);

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $newFile,
        ]);

        $response->assertStatus(200);
        $this->assertSame('icons/'.$newFile->hashName(), $modpack->fresh()->icon);
        Storage::disk()->assertMissing('icons/'.$originalFile->hashName());
        Storage::disk()->assertExists('icons/'.$newFile->hashName());
    }

    /** @test **/
    public function uploading_a_modpack_icon_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $file = UploadedFile::fake()->image('modpack-icon.jpg');
        $modpack = factory(Modpack::class)->create(['icon' => null]);

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(401);
        $this->assertNull($modpack->fresh()->icon);
        Storage::disk()->assertMissing('icons/'.$file->hashName());
    }

    /** @test **/
    public function destroying_a_modpack_icon_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $file = UploadedFile::fake()->image('modpack-icon.jpg');
        $modpack = factory(Modpack::class)->create(['icon' => $file->store('icons')]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}/icon");

        $response->assertStatus(401);
        $this->assertSame('icons/'.$file->hashName(), $modpack->fresh()->icon);
        Storage::disk()->assertExists('icons/'.$file->hashName());
    }

    /** @test **/
    public function destroying_a_modpack_removes_its_icon_from_storage()
    {
        $file = UploadedFile::fake()->image('modpack-icon.jpg');
        $modpack = factory(Modpack::class)->create(['icon' => $file->store('icons')]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Modpack::all());
        Storage::disk()->assertMissing('icons/'.$file->hashName());
    }
}
