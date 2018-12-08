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

use TechnicPack\SolderFramework\Key;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifyKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_validates_keys()
    {
        $key = factory(Key::class)->create(['token' => 'key-token', 'name' => 'My Key']);

        $response = $this->getJson('/api/verify/key-token');

        $response->assertStatus(200);
        $response->assertExactJson([
            'valid'      => 'Key validated.',
            'name'       => 'My Key',
            'created_at' => $key->created_at->toDateTimeString(),
        ]);
    }

    /** @test **/
    public function invalid_key_generates_error()
    {
        $response = $this->getJson('/api/verify/invalid-key');

        $response->assertStatus(200);
        $response->assertExactJson([
            'error' => 'Invalid key provided.',
        ]);
    }
}
