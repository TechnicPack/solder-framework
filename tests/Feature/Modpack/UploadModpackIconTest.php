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
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class UploadModpackIconTest extends TestCase
{
    use RefreshDatabase;

    private $storage;
    private $icon;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(config('solder.disk.icons'));
        $this->storage = Storage::disk(config('solder.disk.icons'));
        $this->icon = UploadedFile::fake()->image('icon.png', 50, 50);
    }

    /** @test **/
    public function an_icon_can_be_uploaded()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->putJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $this->icon,
        ]);

        $response->assertStatus(204);
        $this->assertSame("icons/{$this->icon->hashName()}", $modpack->fresh()->icon_path);
        $this->storage->assertExists("icons/{$this->icon->hashName()}");
    }

    /** @test **/
    public function the_icon_field_is_required()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => null,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon_path);
    }

    /** @test **/
    public function the_icon_must_be_an_image()
    {
        $file = UploadedFile::fake()->create('report.pdf');
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon_path);
    }

    /** @test **/
    public function the_icon_must_be_under_5000_kilobytes()
    {
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $this->icon->size(5001),
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon_path);
    }

    /** @test **/
    public function the_icon_width_must_be_less_than_500_px()
    {
        $file = UploadedFile::fake()->image('icon.png', 501, 50);
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon_path);
    }

    /** @test **/
    public function the_icon_height_must_be_less_than_500_px()
    {
        $file = UploadedFile::fake()->image('icon.png', 50, 501);
        $modpack = factory(Modpack::class)->create();

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $file,
        ]);

        $response->assertStatus(422);
        $this->assertNull($modpack->fresh()->icon_path);
    }

    /** @test **/
    public function an_icon_can_be_destroyed()
    {
        $modpack = factory(Modpack::class)->create([
            'icon_path' => $this->storage->putFile('icons', $this->icon),
        ]);

        $this->storage->assertExists("icons/{$this->icon->hashName()}");

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}/icon");

        $response->assertStatus(204);
        $this->assertNull($modpack->fresh()->icon_path);
        $this->storage->assertMissing("icons/{$this->icon->hashName()}");
    }

    /** @test **/
    public function old_icons_are_removed_from_storage_when_replaced()
    {
        $originalFile = UploadedFile::fake()->image('original.png');
        $modpack = factory(Modpack::class)->create([
            'icon_path' => $this->storage->putFile('icons', $originalFile),
        ]);

        $this->storage->assertExists("icons/{$originalFile->hashName()}");

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $this->icon,
        ]);

        $response->assertStatus(204);
        $this->assertSame("icons/{$this->icon->hashName()}", $modpack->fresh()->icon_path);
        $this->storage->assertMissing("icons/{$originalFile->hashName()}");
        $this->storage->assertExists("icons/{$this->icon->hashName()}");
    }

    /** @test **/
    public function uploading_a_modpack_icon_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create([
            'icon_path' => null,
        ]);

        $response = $this->postJson("/api/modpacks/{$modpack->id}/icon", [
            'icon' => $this->icon,
        ]);

        $response->assertStatus(401);
        $this->assertNull($modpack->fresh()->icon_path);
        $this->storage->assertMissing("icons/{$this->icon->hashName()}");
    }

    /** @test **/
    public function destroying_a_modpack_icon_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create([
            'icon_path' => $this->storage->putFile('icons', $this->icon),
        ]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}/icon");

        $response->assertStatus(401);
        $this->assertSame("icons/{$this->icon->hashName()}", $modpack->fresh()->icon_path);
        $this->storage->assertExists("icons/{$this->icon->hashName()}");
    }
}
