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
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_modpack()
    {
        $modpack = factory(Modpack::class)
            ->state('with-icon')
            ->create(['name' => 'Example A', 'slug' => 'example-a', 'url' => 'http://example.com']);
        factory(Build::class)->create(['tag' => '1.0.0a', 'modpack_id' => $modpack->id]);
        factory(Build::class)->create(['tag' => '1.0.0b', 'modpack_id' => $modpack->id]);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(200);
        $response->assertExactJson([
            'name'         => 'example-a',
            'display_name' => 'Example A',
            'url'          => 'http://example.com',
            'icon'         => Storage::url($modpack->icon_path),
            'recommended'  => null,
            'latest'       => null,
            'builds'       => [
                '1.0.0a',
                '1.0.0b',
            ],
        ]);
    }
}
