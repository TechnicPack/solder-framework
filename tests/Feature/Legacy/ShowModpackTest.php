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

use PHPUnit\Framework\Assert;
use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\TechnicKey;
use TechnicPack\SolderFramework\TechnicClient;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechnicPack\SolderFramework\Http\Legacy\ModpackResource;

class ShowModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_public_modpack()
    {
        $modpack = factory(Modpack::class)->state('public')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(200);
        $response->assertExactJson((new ModpackResource($modpack))->jsonSerialize());
    }

    /** @test **/
    public function suppress_a_hidden_modpack()
    {
        factory(Modpack::class)->state('hidden')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(404);
    }

    /** @test **/
    public function suppress_a_private_modpack()
    {
        factory(Modpack::class)->state('private')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a');

        $response->assertStatus(404);
    }

    /** @test **/
    public function show_private_modpack_with_valid_key()
    {
        factory(TechnicKey::class)->create(['token' => 'valid-token']);
        $modpack = factory(Modpack::class)->state('private')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack/example-a?k=valid-token');

        $response->assertStatus(200);
        $response->assertExactJson((new ModpackResource($modpack))->jsonSerialize());
    }

    /** @test **/
    public function show_private_modpack_with_valid_client()
    {
        $client = factory(TechnicClient::class)->create(['token' => 'valid-token']);
        $modpack = factory(Modpack::class)->state('private')->create(['slug' => 'example-a']);
        $modpack->clients()->attach($client);

        $response = $this->getJson('/api/modpack/example-a?cid=valid-token');

        $response->assertStatus(200);
        $response->assertExactJson((new ModpackResource($modpack))->jsonSerialize());
    }

    /** @test **/
    public function filter_builds_for_public_request()
    {
        // Modpack, Build, Response
        $expectations = collect([
            ['public', 'hidden', 0],
            ['public', 'private', 0],
            ['public', 'public', 1],
        ]);

        $expectations->each(function ($expectation) {
            $modpack = factory(Modpack::class)->state($expectation[0])->create();
            $modpack->builds()->save(factory(Build::class)->state($expectation[1])->make());

            $response = $this->getJson("/api/modpack/{$modpack->slug}");

            Assert::assertCount(
                $expectation[2], data_get($response->json(), 'builds'),
                "Failed to assert that the response count matched the expected {$expectation[2]} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }

    /** @test **/
    public function filter_builds_with_key()
    {
        // Modpack, Build, Response
        $expectations = collect([
            ['private', 'hidden', 0],
            ['private', 'private', 1],
            ['private', 'public', 1],
            ['public', 'hidden', 0],
            ['public', 'private', 1],
            ['public', 'public', 1],
        ]);

        $expectations->each(function ($expectation) {
            $modpack = factory(Modpack::class)->state($expectation[0])->create();
            $modpack->builds()->save(factory(Build::class)->state($expectation[1])->make());
            $key = factory(TechnicKey::class)->create();

            $response = $this->getJson("/api/modpack/{$modpack->slug}?k={$key->token}");

            Assert::assertCount(
                $expectation[2], data_get($response->json(), 'builds'),
                "Failed to assert that the response count matched the expected {$expectation[2]} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }

    /** @test **/
    public function filter_builds_with_client()
    {
        // Modpack, Build, Response
        $expectations = collect([
            ['private', 'hidden', 0],
            ['private', 'private', 1],
            ['private', 'public', 1],
            ['public', 'hidden', 0],
            ['public', 'private', 1],
            ['public', 'public', 1],
        ]);

        $expectations->each(function ($expectation) {
            $modpack = factory(Modpack::class)->state($expectation[0])->create();
            $modpack->builds()->save(factory(Build::class)->state($expectation[1])->make());
            $modpack->clients()->attach($client = factory(TechnicClient::class)->create());

            $response = $this->getJson("/api/modpack/{$modpack->slug}?cid={$client->token}");

            Assert::assertCount(
                $expectation[2], data_get($response->json(), 'builds'),
                "Failed to assert that the response count matched the expected {$expectation[2]} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }
}
