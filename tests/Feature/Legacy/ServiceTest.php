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

use TechnicPack\SolderFramework\Solder;
use TechnicPack\SolderFramework\Tests\TestCase;

class ServiceTest extends TestCase
{
    /** @test **/
    public function it_describes_the_api()
    {
        $response = $this->getJson('/api');

        $response->assertStatus(200);
        $response->assertExactJson([
            'api'     => 'Solder Framework',
            'version' => Solder::version(),
            'links'   => [
                [
                    'href' => '/api/modpack',
                    'rel'  => 'modpacks',
                    'type' => 'GET',
                ],
            ],
        ]);
    }
}
