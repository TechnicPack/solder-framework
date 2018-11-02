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
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Models\Modpack;
use Illuminate\Routing\Controller as BaseController;

class ModpackController extends BaseController
{
    /**
     * ModpackController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Modpack::all());
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

        $modpack = Modpack::create($attributes);

        return response()->json($modpack, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Modpack $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Modpack $modpack)
    {
        return response()->json($modpack);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Modpack                  $modpack
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modpack $modpack)
    {
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
     * @param Modpack $modpack
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack)
    {
        $modpack->delete();

        return response()->json([], 204);
    }
}
