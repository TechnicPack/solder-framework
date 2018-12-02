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

class UpdateModpackTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_modpack_can_be_updated()
    {
        $modpack = factory(Modpack::class)->create([
            'name' => 'Existing Modpack',
            'slug' => 'existing-modpack',
            'url'  => 'http://example.com/original',
        ]);

        $response = $this->putJson("/api/modpacks/{$modpack->id}", [
            'name' => 'Revised Modpack',
            'slug' => 'revised-modpack',
            'url'  => 'http://example.com/revised',
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, Modpack::all());
        tap(Modpack::first(), function ($modpack) use ($response) {
            $this->assertSame('Revised Modpack', $modpack->name);
            $this->assertSame('revised-modpack', $modpack->slug);
            $this->assertSame('http://example.com/revised', $modpack->url);

            $response->assertJsonFragment([
                'id'   => $modpack->id,
                'name' => 'Revised Modpack',
                'slug' => 'revised-modpack',
                'url'  => 'http://example.com/revised',
            ]);
        });
    }

    /** @test **/
    public function updating_a_modpack_requires_authentication()
    {
        $this->withMiddleware([
            Authenticate::class,
        ]);

        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams());

        $response->assertStatus(401);
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function updating_an_invalid_modpack_returns_a_404_error()
    {
        $response = $this->putJson('/api/modpacks/99', $this->validParams());

        $response->assertStatus(404);
    }

    /** @test */
    public function the_name_field_is_required_to_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function the_slug_field_is_required_to_update_a_modpack()
    {
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('slug');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function the_slug_field_must_be_unique_to_update_a_modpack()
    {
        factory(Modpack::class)->create(['slug' => 'duplicate-modpack']);
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams([
            'slug' => 'duplicate-modpack',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('slug');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function the_original_slug_field_may_be_resubmitted_when_updating_a_modpack()
    {
        $modpack = factory(Modpack::class)->create($this->originalParams([
            'slug' => 'existing-modpack',
        ]));

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams([
            'slug' => 'existing-modpack',
        ]));

        $response->assertStatus(200);
        $this->assertArraySubset($this->validParams(['slug' => 'existing-modpack']), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function the_url_may_be_null()
    {
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams([
            'url' => '',
        ]));

        $response->assertStatus(200);
        $this->assertArraySubset($this->validParams(['url' => null]), $modpack->fresh()->getAttributes());
    }

    /** @test */
    public function the_url_must_be_formatted_correctly()
    {
        $modpack = factory(Modpack::class)->create($this->originalParams());

        $response = $this->putJson("/api/modpacks/{$modpack->id}", $this->validParams([
            'url' => 'not a url',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('url');
        $this->assertArraySubset($this->originalParams(), $modpack->fresh()->getAttributes());
    }

    private function originalParams($overrides = [])
    {
        return array_merge([
            'name' => 'Existing Modpack',
            'slug' => 'existing-modpack',
            'url'  => 'http://example.com/original',
        ], $overrides);
    }

    /**
     * @param array $overrides
     *
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Revised Modpack',
            'slug' => 'revised-modpack',
            'url'  => 'http://example.com/revised',
        ], $overrides);
    }
}
