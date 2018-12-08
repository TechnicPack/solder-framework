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
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\ModpackResource;

class ListModpacksTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function list_modpacks()
    {
        factory(Modpack::class)->create(['slug' => 'example-a', 'name' => 'Example A']);
        factory(Modpack::class)->create(['slug' => 'example-b', 'name' => 'Example B']);

        $response = $this->getJson('/api/modpack');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'modpacks');
        $response->assertExactJson([
            'modpacks' => [
                'example-a' => 'Example A',
                'example-b' => 'Example B',
            ],
            'mirror_url' => null,
        ]);
    }

    /** @test **/
    public function list_modpacks_with_details()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack?include=full');

        $response->assertStatus(200);
        $response->assertExactJson([
            'modpacks' => [
                'example-a' => (new ModpackResource($modpack))->jsonSerialize(),
            ],
            'mirror_url' => null,
        ]);
    }
}
