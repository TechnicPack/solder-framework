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
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class PackageController extends BaseController
{
    /**
     * The version model.
     *
     * @var \TechnicPack\SolderFramework\Version
     */
    protected $version;

    /**
     * The mod model.
     *
     * @var \TechnicPack\SolderFramework\Mod
     */
    protected $mod;

    /**
     * VersionController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->version = config('solder.model.version');
        $this->mod = config('solder.model.mod');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $version = $this->version::whereMod($request->mod)
            ->findOrFail($request->version);

        $request->validate([
            'package' => ['required', 'mimes:zip'],
        ]);

        $version->setPackage($request->package);

        return response()->json($version, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $version = $this->version::whereMod($request->mod)
            ->findOrFail($request->version);

        $version->setPackage(null);

        return response()->json([], 204);
    }
}
