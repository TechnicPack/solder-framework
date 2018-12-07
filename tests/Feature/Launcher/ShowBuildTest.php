<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature\Launcher;

use TechnicPack\SolderFramework\Build;
use TechnicPack\SolderFramework\Version;
use TechnicPack\SolderFramework\Dependency;
use TechnicPack\SolderFramework\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowBuildTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function show_a_build()
    {
        $build = factory(Build::class)->create(['tag' => '1.0.0a']);
        $versionA = factory(Version::class)->state('with-package')->create();
        $versionB = factory(Version::class)->state('with-package')->create();
        $build->dependencies()->saveMany([
            $dependencyA = factory(Dependency::class)->create(['version_id' => $versionA]),
            $dependencyB = factory(Dependency::class)->create(['version_id' => $versionB]),
        ]);

        $response = $this->getJson("/api/modpack/{$build->modpack->slug}/{$build->tag}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'minecraft' => $build->minecraft_version,
            'java'      => $build->java_version,
            'memory'    => $build->java_memory,
            'mods'      => [
                [
                    'name'    => $dependencyA->mod->modid,
                    'version' => $dependencyA->version->tag,
                    'md5'     => $dependencyA->version->package_hash,
                    'url'     => $dependencyA->version->package_url,
                ],
                [
                    'name'    => $dependencyB->mod->modid,
                    'version' => $dependencyB->version->tag,
                    'md5'     => $dependencyB->version->package_hash,
                    'url'     => $dependencyB->version->package_url,
                ],
            ],
        ]);
    }

    /** @test **/
    public function show_a_build_with_full_mod_details()
    {
        $build = factory(Build::class)->create(['tag' => '1.0.0a']);
        $versionA = factory(Version::class)->state('with-package')->create();
        $versionB = factory(Version::class)->state('with-package')->create();
        $build->dependencies()->saveMany([
            $dependencyA = factory(Dependency::class)->create(['version_id' => $versionA]),
            $dependencyB = factory(Dependency::class)->create(['version_id' => $versionB]),
        ]);

        $response = $this->getJson("/api/modpack/{$build->modpack->slug}/{$build->tag}?include=mods");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name'        => $dependencyA->mod->modid,
            'version'     => $dependencyA->version->tag,
            'md5'         => $dependencyA->version->package_hash,
            'url'         => $dependencyA->version->package_url,
            'pretty_name' => $dependencyA->mod->name,
            'author'      => $dependencyA->mod->author,
            'description' => $dependencyA->mod->description,
            'link'        => $dependencyA->mod->url,
        ]);
        $response->assertJsonFragment([
            'name'        => $dependencyB->mod->modid,
            'version'     => $dependencyB->version->tag,
            'md5'         => $dependencyB->version->package_hash,
            'url'         => $dependencyB->version->package_url,
            'pretty_name' => $dependencyB->mod->name,
            'author'      => $dependencyB->mod->author,
            'description' => $dependencyB->mod->description,
            'link'        => $dependencyB->mod->url,
        ]);
    }
}
