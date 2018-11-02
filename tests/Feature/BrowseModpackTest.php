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

class BrowseModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function browse_modpack_list()
    {
        $modpackA = factory(Modpack::class)->create();
        $modpackB = factory(Modpack::class)->create();

        $response = $this->getJson('/api/modpacks');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['name' => $modpackA->name]);
        $response->assertJsonFragment(['name' => $modpackB->name]);
    }
}
