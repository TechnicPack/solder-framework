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
use Spatie\QueryBuilder\QueryBuilder;
use TechnicPack\SolderFramework\Rules\Unique;
use TechnicPack\SolderFramework\Rules\Lowercase;
use Illuminate\Routing\Controller as BaseController;

class ModController extends BaseController
{
    /**
     * The mod model.
     *
     * @var Mod
     */
    protected $mod;

    /**
     * ModController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->mod = config('solder.model.mod');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mods = QueryBuilder::for($this->mod)
            ->allowedIncludes('versions')
            ->get();

        return response()->json($mods);
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
        $attributes = $request->validate([
            'name'        => ['required'],
            'modid'       => ['required', 'alpha_dash', 'max:64', new Lowercase(), new Unique($this->mod)],
            'author'      => ['nullable'],
            'url'         => ['nullable', 'url'],
            'description' => ['nullable'],
        ]);

        $mod = $this->mod::create($attributes);

        return response()->json($mod, 201);
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
        $mod = QueryBuilder::for($this->mod)
            ->allowedIncludes('versions')
            ->findOrFail($request->mod);

        return response()->json($mod);
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
        $mod = $this->mod::findOrFail($request->mod);
        $attributes = $request->validate([
            'name'        => ['required'],
            'modid'       => ['required', 'alpha_dash', 'max:64', new Lowercase(), new Unique($this->mod, $mod->id)],
            'author'      => ['nullable'],
            'url'         => ['nullable', 'url'],
            'description' => ['nullable'],
        ]);

        $mod->update($attributes);

        return response()->json($mod);
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
        $mod = $this->mod::findOrFail($request->mod);
        $mod->delete();

        return response()->json([], 204);
    }
}
