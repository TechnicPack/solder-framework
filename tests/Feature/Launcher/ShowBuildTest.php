<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Launcher;

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_build()
    {
        $build = factory(Build::class)->create(['tag' => '1.0.0a']);

        $response = $this->getJson("/api/modpack/{$build->modpack->slug}/{$build->tag}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'minecraft' => $build->minecraft_version,
            'java'      => $build->java_version,
            'memory'    => $build->java_memory,
            'mods'      => [],
        ]);
    }
}
