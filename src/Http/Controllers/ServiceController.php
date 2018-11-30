<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Http\Controllers;

use TechnicPack\SolderFramework\Solder;
use Illuminate\Routing\Controller as BaseController;

class ServiceController extends BaseController
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json([
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
