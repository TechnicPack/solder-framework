<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Modpack;

use TechnicPack\SolderFramework\Modpack;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Http\Middleware\Authenticate;

class StoreModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_stored()
    {
        $response = $this->postJson('/api/modpacks', [
            'name'       => 'Test Modpack',
            'slug'       => 'test-modpack',
            'url'        => 'http://example.com/example-mod',
            'visibility' => 'private',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Modpack::all());
        tap(Modpack::first(), function ($modpack) use ($response) {
            $this->assertSame('Test Modpack', $modpack->name);
            $this->assertSame('test-modpack', $modpack->slug);
            $this->assertSame('http://example.com/example-mod', $modpack->url);
            $this->assertSame('private', $modpack->visibility);
            $response->assertExactJson($modpack->toArray());
        });
    }

    /** @test **/
    public function storing_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $response = $this->postJson('/api/modpacks', $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, Modpack::all());
    }

    /** @test */
    public function the_name_field_is_required_to_store_a_modpack()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Modpack::count());
    }

    /** @test */
    public function the_slug_field_is_required_to_store_a_modpack()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Modpack::count());
    }

    /** @test */
    public function the_slug_field_must_be_unique_to_store_a_modpack()
    {
        factory(Modpack::class)->create(['slug' => 'existing-modpack']);

        $response = $this->postJson('/api/modpacks', $this->validParams([
            'slug' => 'existing-modpack',
        ]));

        $response->assertStatus(422);
        $this->assertSame(1, Modpack::count());
    }

    /** @test */
    public function the_slug_field_must_not_contain_spaces_or_special_characters()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'slug' => 'spaces and symbols!',
        ]));

        $response->assertJsonValidationErrors('slug');
        $this->assertSame(0, Modpack::count());
    }

    /** @test **/
    public function the_url_field_may_be_empty()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'url' => '',
        ]));

        $response->assertStatus(201);
        $response->assertJsonMissingValidationErrors('url');
        $this->assertCount(1, Modpack::all());
    }

    /** @test **/
    public function the_url_field_must_be_a_url()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'url' => 'search google',
        ]));

        $response->assertJsonValidationErrors('url');
        $this->assertSame(0, Modpack::count());
    }

    /** @test **/
    public function the_visibility_field_is_required()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'visibility' => '',
        ]));

        $response->assertJsonValidationErrors('visibility');
        $this->assertSame(0, Modpack::count());
    }

    /** @test **/
    public function the_visibility_field_must_be_in_list()
    {
        $response = $this->postJson('/api/modpacks', $this->validParams([
            'visibility' => 'transcended',
        ]));

        $response->assertJsonValidationErrors('visibility');
        $this->assertSame(0, Modpack::count());
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_merge([
            'name'       => 'Test Modpack',
            'slug'       => 'test-modpack',
            'url'        => 'http://example.com/example-mod',
            'visibility' => 'public',
        ], $overrides);
    }
}
