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

class ListModpacksTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function list_public_modpacks()
    {
        factory(Modpack::class)->state('public')->create(['slug' => 'example-a', 'name' => 'Example A']);
        factory(Modpack::class)->state('public')->create(['slug' => 'example-b', 'name' => 'Example B']);

        $response = $this->getJson('/api/modpack');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'modpacks');
        $response->assertExactJson([
            'modpacks' => [
                'example-a' => 'Example A',
                'example-b' => 'Example B',
            ],
            'mirror_url' => null,
        ]);
    }

    /** @test **/
    public function list_modpacks_with_details()
    {
        $modpack = factory(Modpack::class)->state('public')->create(['slug' => 'example-a']);

        $response = $this->getJson('/api/modpack?include=full');

        $response->assertStatus(200);
        $response->assertExactJson([
            'modpacks' => [
                'example-a' => (new ModpackResource($modpack))->jsonSerialize(),
            ],
            'mirror_url' => null,
        ]);
    }

    /** @test **/
    public function hide_private_modpacks()
    {
        factory(Modpack::class)->state('private')->create();

        $response = $this->getJson('/api/modpack');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'modpacks');
    }

    /** @test **/
    public function hide_hidden_modpacks()
    {
        factory(Modpack::class)->state('hidden')->create();

        $response = $this->getJson('/api/modpack');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'modpacks');
    }

    /** @test **/
    public function list_private_modpacks_with_valid_key()
    {
        factory(Modpack::class)->state('private')->create();
        factory(TechnicKey::class)->create(['token' => 'valid-key']);

        $response = $this->getJson('/api/modpack?k=valid-key');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'modpacks');
    }

    /** @test **/
    public function list_private_modpacks_with_valid_client()
    {
        $modpack = factory(Modpack::class)->state('private')->create();
        $client = factory(TechnicClient::class)->create(['token' => 'valid-client']);
        $modpack->clients()->attach($client);

        $response = $this->getJson('/api/modpack?cid=valid-client');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'modpacks');
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

        $modpack = factory(Modpack::class)->create();
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id]);

        $expectations->each(function ($expectation) use ($modpack, $build) {
            $modpack->update(['visibility' => $expectation[0]]);
            $build->update(['visibility' => $expectation[1]]);

            $response = $this->getJson('/api/modpack?include=full');

            $builds = data_get(array_first(data_get($response->json(), 'modpacks')), 'builds');
            Assert::assertCount(
                $expectation[2], $builds,
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

        $modpack = factory(Modpack::class)->create();
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id]);
        $key = factory(TechnicKey::class)->create(['token' => 'valid-key']);

        $expectations->each(function ($expectation) use ($build, $modpack) {
            $modpack->update(['visibility' => $expectation[0]]);
            $build->update(['visibility' => $expectation[1]]);

            $response = $this->getJson('/api/modpack?include=full&k=valid-key');
            $builds = data_get(array_first(data_get($response->json(), 'modpacks')), 'builds');

            Assert::assertCount(
                $expectation[2], $builds,
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

        $modpack = factory(Modpack::class)->create();
        $build = factory(Build::class)->create(['modpack_id' => $modpack->id]);
        $client = factory(TechnicClient::class)->create(['token' => 'valid-client']);
        $modpack->clients()->attach($client);

        $expectations->each(function ($expectation) use ($build, $modpack) {
            $modpack->update(['visibility' => $expectation[0]]);
            $build->update(['visibility' => $expectation[1]]);

            $response = $this->getJson('/api/modpack?include=full&cid=valid-client');
            $builds = data_get(array_first(data_get($response->json(), 'modpacks')), 'builds');

            Assert::assertCount(
                $expectation[2], $builds,
                "Failed to assert that the response count matched the expected {$expectation[2]} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }
}
