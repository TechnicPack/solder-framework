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
use TechnicPack\SolderFramework\Models\Modpack;
use Illuminate\Routing\Controller as BaseController;

class ModpackController extends BaseController
{
    /**
     * The modpack model.
     *
     * @var Modpack
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
        return response()->json($this->modpack::all());
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
            'slug' => ['required', Rule::unique('modpacks')],
        ]);

        $modpack = $this->modpack::create($attributes);

        return response()->json($modpack, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $modpackId
     *
     * @return \Illuminate\Http\Response
     */
    public function show($modpackId)
    {
        $modpack = $this->modpack::findOrFail($modpackId);

        return response()->json($modpack);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $modpackId
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $modpackId)
    {
        $modpack = $this->modpack::findOrFail($modpackId);

        $attributes = $request->validate([
            'name' => ['required'],
            'slug' => ['required', Rule::unique('modpacks')->ignore($modpack->id)],
        ]);

        $modpack->update($attributes);

        return response()->json($modpack);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $modpackId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($modpackId)
    {
        $modpack = $this->modpack::findOrFail($modpackId);
        $modpack->delete();

        return response()->json([], 204);
    }
}
