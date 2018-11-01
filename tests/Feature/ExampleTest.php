<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Tests\Feature;

use TechnicPack\SolderFramework\Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test **/
    public function testBasicTest()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api');

        $response->assertStatus(200);
    }
}
