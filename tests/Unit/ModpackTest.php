<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModpackTest extends TestCase
{
    use RefreshDatabase;

    private $filesystem;

    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = Storage::disk(config('solder.disk.icons'));
    }

    /** @test **/
    public function it_is_serialized_to_json()
    {
        $modpack = factory(Modpack::class)->create([
            'name'      => 'Example Modpack',
            'slug'      => 'example-modpack',
            'url'       => 'http://example.com/example-mod',
            'icon_path' => 'icon.png',
        ]);

        $this->assertArraySubset([
            'id'         => $modpack->id,
            'name'       => 'Example Modpack',
            'slug'       => 'example-modpack',
            'url'        => 'http://example.com/example-mod',
            'icon'       => $this->filesystem->url('icon.png'),
            'updated_at' => $modpack->updated_at->toDateTimeString(),
            'created_at' => $modpack->created_at->toDateTimeString(),
        ], $modpack->jsonSerialize());
    }
}
