<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\PlatformKey;

use TechnicPack\SolderFramework\PlatformKey;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ListPlatformKeysTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_lists_keys()
    {
        $keyA = factory(PlatformKey::class)->create();
        $keyB = factory(PlatformKey::class)->create();

        $response = $this->getJson('/api/platform-keys');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertExactJson([
            $keyA->toArray(),
            $keyB->toArray(),
        ]);
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->getJson('/api/platform-keys');

        $response->assertStatus(401);
    }
}
