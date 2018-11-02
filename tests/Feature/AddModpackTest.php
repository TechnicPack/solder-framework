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

class AddModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function add_a_modpack()
    {
        $response = $this->postJson('/api/modpacks', [
            'name' => 'Test Modpack',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Test Modpack']);
        $this->assertCount(1, Modpack::all());
    }
}
