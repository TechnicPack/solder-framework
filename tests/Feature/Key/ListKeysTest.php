<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Key;

use TechnicPack\SolderFramework\Key;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class ListKeysTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_lists_keys()
    {
        $keyA = factory(Key::class)->create();
        $keyB = factory(Key::class)->create();

        $response = $this->getJson('/api/keys');

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

        $response = $this->getJson('/api/keys');

        $response->assertStatus(401);
    }
}
