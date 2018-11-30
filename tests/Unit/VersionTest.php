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
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function package_meta_can_be_refreshed_from_storage()
    {
        Storage::fake(config('solder.disk.files'));
        $file = \Illuminate\Http\UploadedFile::fake()->image('example_package-1.0.0.zip');
        $version = factory(Version::class)->create([
            'package'      => $file->store('files', ['disk' => config('solder.disk.files')]),
            'package_size' => 0,
            'package_hash' => 'some-old-or-invalid-hash',
        ]);

        $version->refreshPackageMeta();

        tap($version->fresh(), function ($version) use ($file) {
            $this->assertSame($file->getSize(), $version->package_size);
            $this->assertSame($file->getHash(), $version->package_hash);
        });
    }
}
