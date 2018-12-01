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
use TechnicPack\SolderFramework\Rules\UrlSafe;
use Illuminate\Routing\Controller as BaseController;

class ModpackController extends BaseController
{
    /**
     * The modpack model.
     *
     * @var \TechnicPack\SolderFramework\Modpack
     */
    protected $modpack;

    /**
     * ModpackController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->modpack = config('solder.model.modpack');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modpacks = QueryBuilder::for($this->modpack)
            ->allowedIncludes('builds')
            ->get();

        return response()->json($modpacks);
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
            'name' => ['required'],
            'slug' => ['required', new UrlSafe(), new Unique($this->modpack)],
        ]);

        $modpack = $this->modpack::create($attributes);

        return response()->json($modpack, 201);
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
        $modpack = QueryBuilder::for($this->modpack)
            ->allowedIncludes('builds', 'latest', 'recommended')
            ->findOrFail($request->modpack);

        return response()->json($modpack);
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
        $modpack = $this->modpack::findOrFail($request->modpack);

        $attributes = $request->validate([
            'name' => ['required'],
            'slug' => ['required', new Unique($this->modpack, $modpack->id)],
        ]);

        $modpack->update($attributes);

        return response()->json($modpack);
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
        $modpack = $this->modpack::findOrFail($request->modpack);
        $modpack->delete();

        return response()->json([], 204);
    }
}
