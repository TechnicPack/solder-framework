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

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BuildVisibilityController extends BaseController
{
    /**
     * The build model.
     *
     * @var \TechnicPack\SolderFramework\Build
     */
    protected $build;

    /**
     * BuildController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->build = config('solder.model.build');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $build = $this->build::findOrFail($request->build);

        $request->validate([
            'visibility' => ['required', 'in:hidden,private,public'],
        ]);

        $build->visibility = $request->get('visibility');
        $build->save();

        return response()->json($build);
    }
}
