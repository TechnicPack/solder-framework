<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature;

use TechnicPack\SolderFramework\Models\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_modpack_details()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Example Modpack',
        ]);

        $response = $this->getJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Example Modpack']);
    }
}
