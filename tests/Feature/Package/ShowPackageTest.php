<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Package;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ShowPackageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Path to package fixture.
     *
     * @var string
     */
    private $packageFixture;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake(config('solder.disk.files'));
        $this->packageFixture = __DIR__.'/../../__fixtures__/package.zip';
    }

    /** @test **/
    public function list_package_contents()
    {
        $version = factory(Version::class)->create();
        $version->setPackage(new UploadedFile($this->packageFixture, basename($this->packageFixture)));

        $response = $this->getJson("/api/versions/{$version->id}/package");

        $response->assertStatus(200);
        $response->assertExactJson([
            [
                'path'     => 'mods',
                'type'     => 'dir',
                'dirname'  => '',
                'basename' => 'mods',
                'filename' => 'mods',
            ], [
                'type'      => 'file',
                'size'      => 1702,
                'timestamp' => 1544996146,
                'path'      => 'mods/examplemod-1.0.jar',
                'dirname'   => 'mods',
                'basename'  => 'examplemod-1.0.jar',
                'extension' => 'jar',
                'filename'  => 'examplemod-1.0',
            ],
        ]);
    }

    /** @test **/
    public function listing_package_contents_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $version = factory(Version::class)->create();
        $version->setPackage(new UploadedFile($this->packageFixture, basename($this->packageFixture)));

        $response = $this->getJson("/api/versions/{$version->id}/package");

        $response->assertStatus(401);
    }
}
