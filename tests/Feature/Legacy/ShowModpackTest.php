<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Legacy;

use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\PlatformKey;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\ModpackResource;

class ShowModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->state('public')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(200);
        $response->assertExactJson((new ModpackResource($modpack))->jsonSerialize());
    }

    /** @test **/
    public function suppress_a_hidden_modpack()
    {
        factory(Modpack::class)->state('hidden')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(404);
    }

    /** @test **/
    public function suppress_a_private_modpack()
    {
        factory(Modpack::class)->state('private')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(404);
    }

    /** @test **/
    public function show_private_modpack_with_valid_key()
    {
        factory(PlatformKey::class)->create(['token' => 'valid-token']);
        $modpack = factory(Modpack::class)->state('private')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a?k=valid-token');

        $response->assertStatus(200);
        $response->assertExactJson((new ModpackResource($modpack))->jsonSerialize());
    }
}
