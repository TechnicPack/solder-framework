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

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\BuildResource;

class ShowBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_build()
    {
        $modpack = factory(Modpack::class)->create(['slug' => 'example-modpack']);
        $build = factory(Build::class)->create(['tag' => '1.0.0', 'modpack_id' => $modpack->id]);

        $response = $this->getJson('/api/modpack/example-modpack/1.0.0');

        $response->assertStatus(200);
        $response->assertExactJson((new BuildResource($build))->jsonSerialize());
    }
}
