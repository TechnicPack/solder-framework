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

class EditModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function edit_a_modpack()
    {
        $this->withoutExceptionHandling();
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => 'Revised Modpack',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Revised Modpack']);
        $this->assertCount(1, Modpack::all());
        $this->assertSame('Revised Modpack', $modpack->fresh()->name);
    }
}
