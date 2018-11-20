<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Mod;

use TechnicPack\SolderFramework\Mod;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class StoreModTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_stores_a_mod()
    {
        $response = $this->postJson('/api/mods', [
            'name'        => 'Test Mod',
            'modid'       => 'test-mod',
            'author'      => 'John Doe',
            'url'         => 'http://google.com',
            'description' => 'Mod description.',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Mod::all());
        $response->assertJsonFragment([
            'name'        => 'Test Mod',
            'modid'       => 'test-mod',
            'author'      => 'John Doe',
            'url'         => 'http://google.com',
            'description' => 'Mod description.',
        ]);
    }

    /** @test **/
    public function it_drops_unauthenticated_requests()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/mods', $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, Mod::all());
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function modid_is_required()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function modid_is_unique()
    {
        factory(Mod::class)->create(['modid' => 'existing-mod']);

        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => 'existing-mod',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(1, Mod::count());
    }

    /** @test */
    public function modid_cannot_contain_symbols()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => 'aa!',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function modid_cannot_contain_spaces()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => 'a a',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function modid_cannot_contain_capitals()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => 'AA',
        ]));
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function modid_cannot_exceed_64_characters()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'modid' => str_repeat('a', 65),
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modid');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function author_is_optional()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'author' => '',
        ]));

        $response->assertStatus(201);
        $this->assertCount(1, Mod::all());
    }

    /** @test */
    public function url_is_optional()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'url' => '',
        ]));

        $response->assertStatus(201);
        $this->assertCount(1, Mod::all());
    }

    /** @test */
    public function url_is_valid()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'url' => 'not-a-url',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('url');
        $this->assertSame(0, Mod::count());
    }

    /** @test */
    public function description_is_optional()
    {
        $response = $this->postJson('/api/mods', $this->validParams([
            'description' => '',
        ]));

        $response->assertStatus(201);
        $this->assertCount(1, Mod::all());
    }

    /**
     * Return a set of valid build creation parameters.
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'name'  => 'Test Mod',
            'modid' => 'test-mod',
        ], $overrides);
    }
}
