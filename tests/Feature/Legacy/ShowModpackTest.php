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

class ShowModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_modpack()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(200);
        $response->assertExactJson((new ModpackResource($modpack))->jsonSerialize());
    }
}
