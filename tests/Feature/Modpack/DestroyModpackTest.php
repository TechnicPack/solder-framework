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
use TechnicPack\SolderFramework\Build;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class DestroyModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_destroyed()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
        ]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Modpack::all());
    }

    /** @test **/
    public function destroying_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create();

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(401);
        $this->assertCount(1, Modpack::all());
    }

    /** @test **/
    public function destroying_a_modpack_is_rate_limited()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
        ]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $this->assertNotNull($response->headers->get('X-Ratelimit-Limit'));
    }

    /** @test */
    public function destroying_an_invalid_modpack_returns_a_404_error()
    {
        $response = $this->deleteJson('/api/modpacks/99');

        $response->assertStatus(404);
    }

    /** @test **/
    public function destroying_a_modpack_removes_the_icon_from_storage()
    {
        Storage::fake(config('solder.disk.icons'));
        $storage = Storage::disk(config('solder.disk.icons'));
        $icon = UploadedFile::fake()->image('icon.png', 50, 50);

        $modpack = factory(Modpack::class)->create([
            'icon_path' => $storage->putFile('icons', $icon),
        ]);

        $this->deleteJson("/api/modpacks/{$modpack->id}");

        $storage->assertMissing("icons/{$icon->hashName()}");
    }

    /** @test **/
    public function destroying_a_modpack_removes_related_builds()
    {
        $modpack = factory(Modpack::class)->create();
        $modpack->builds()->save(factory(Build::class)->make());

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Build::all());
    }
}
