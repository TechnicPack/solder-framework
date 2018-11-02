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

class DeleteModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function delete_a_modpack()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
        ]);

        $response = $this->deleteJson("/api/modpacks/{$modpack->id}");

        $response->assertStatus(204);
        $this->assertCount(0, Modpack::all());
    }
}
