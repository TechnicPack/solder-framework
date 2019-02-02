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
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Routing\Controller as BaseController;

class ModVersionController extends BaseController
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
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $versions = QueryBuilder::for($this->version)
            ->whereMod($request->mod)
            ->get();

        return response()->json($versions);
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
        $mod = $this->mod::findOrFail($request->mod);
        $unique = Rule::unique('versions')->where('mod_id', $mod->id);

        $attributes = $request->validate([
            'tag'     => ['required', $unique],
            'package' => ['required', 'mimes:zip'],
        ]);

        $version = $mod->versions()->create($attributes);
        $version->setPackage($request->file('package'));

        return response()->json($version, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $version = QueryBuilder::for($this->version)
            ->findOrFail($request->version);

        return response()->json($version);
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
        $version = $this->version::whereMod($request->mod)
            ->findOrFail($request->version);

        $unique = Rule::unique('versions')
            ->where('mod_id', $request->mod)
            ->ignore($version->id);

        $attributes = $request->validate([
            'tag' => ['required', $unique],
        ]);

        $version->update($attributes);

        return response()->json($version);
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
        $version = $this->version::findOrFail($request->version);
        $version->delete();

        return response()->json([], 204);
    }
}
