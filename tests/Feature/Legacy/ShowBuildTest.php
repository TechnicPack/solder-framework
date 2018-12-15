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

class ShowBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_build_publicly()
    {
        // Modpack, Build, Response
        $expectations = collect([
            ['hidden', 'hidden', 404],
            ['hidden', 'private', 404],
            ['hidden', 'public', 404],
            ['private', 'hidden', 404],
            ['private', 'private', 404],
            ['private', 'public', 404],
            ['public', 'hidden', 404],
            ['public', 'private', 404],
            ['public', 'public', 200],
        ]);

        $expectations->each(function ($expectation) {
            $modpack = factory(Modpack::class)->state($expectation[0])->create();
            $build = $modpack->builds()->save(factory(Build::class)->state($expectation[1])->make());

            $response = $this->getJson("/api/modpack/{$modpack->slug}/{$build->tag}");

            $actual = $response->getStatusCode();
            Assert::assertTrue(
                $actual === $expectation[2],
                "Expected status code {$expectation[2]} but received {$actual} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }

    /** @test **/
    public function show_a_build_with_valid_key()
    {
        // Modpack, Build, Response
        $expectations = collect([
            ['hidden', 'hidden', 404],
            ['hidden', 'private', 404],
            ['hidden', 'public', 404],
            ['private', 'hidden', 404],
            ['private', 'private', 200],
            ['private', 'public', 200],
            ['public', 'hidden', 404],
            ['public', 'private', 200],
            ['public', 'public', 200],
        ]);

        $expectations->each(function ($expectation) {
            $key = factory(TechnicKey::class)->create();
            $modpack = factory(Modpack::class)->state($expectation[0])->create();
            $build = $modpack->builds()->save(factory(Build::class)->state($expectation[1])->make());

            $response = $this->getJson("/api/modpack/{$modpack->slug}/{$build->tag}?k={$key->token}");

            $actual = $response->getStatusCode();
            Assert::assertTrue(
                $actual === $expectation[2],
                "Expected status code {$expectation[2]} but received {$actual} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }

    /** @test **/
    public function show_a_build_with_valid_client()
    {
        // Modpack, Build, Response
        $expectations = collect([
            ['hidden', 'hidden', 404],
            ['hidden', 'private', 404],
            ['hidden', 'public', 404],
            ['private', 'hidden', 404],
            ['private', 'private', 200],
            ['private', 'public', 200],
            ['public', 'hidden', 404],
            ['public', 'private', 200],
            ['public', 'public', 200],
        ]);

        $expectations->each(function ($expectation) {
            $client = factory(TechnicClient::class)->create();
            $modpack = factory(Modpack::class)->state($expectation[0])->create();
            $build = $modpack->builds()->save(factory(Build::class)->state($expectation[1])->make());
            $modpack->clients()->attach($client);

            $response = $this->getJson("/api/modpack/{$modpack->slug}/{$build->tag}?cid={$client->token}");

            $actual = $response->getStatusCode();
            Assert::assertTrue(
                $actual === $expectation[2],
                "Expected status code {$expectation[2]} but received {$actual} for {$expectation[0]} modpack and {$expectation[1]} build."
            );
        });
    }
}
