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

class PlatformKeyController extends BaseController
{
    /**
     * The key model.
     *
     * @var \TechnicPack\SolderFramework\PlatformKey
     */
    protected $platformKey;

    /**
     * KeyController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->platformKey = config('solder.model.platformKey');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keys = $this->platformKey::all();

        return response()->json($keys);
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
            'name'  => ['required'],
            'token' => ['required'],
        ]);

        $key = $this->platformKey::create($attributes);

        return response()->json($key, 201);
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
        $key = $this->platformKey::findOrFail($request->route('platform_key'));

        return response()->json($key);
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
        $key = $this->platformKey::findOrFail($request->route('platform_key'));

        $attributes = $request->validate([
            'name'  => ['required'],
            'token' => ['required'],
        ]);

        $key->update($attributes);

        return response()->json($key);
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
        $key = $this->platformKey::findOrFail($request->route('platform_key'));
        $key->delete();

        return response()->json([], 204);
    }
}
