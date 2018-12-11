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

class LauncherClientController extends BaseController
{
    /**
     * The LauncherClient model.
     *
     * @var \TechnicPack\SolderFramework\LauncherClient
     */
    protected $launcherClient;

    /**
     * KeyController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->launcherClient = config('solder.model.launcherClient');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = $this->launcherClient::all();

        return response()->json($clients);
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

        $client = $this->launcherClient::create($attributes);

        return response()->json($client, 201);
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
        $client = $this->launcherClient::findOrFail($request->route('launcher_client'));

        return response()->json($client);
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
        $client = $this->launcherClient::findOrFail($request->route('launcher_client'));

        $attributes = $request->validate([
            'name'  => ['required'],
            'token' => ['required'],
        ]);

        $client->update($attributes);

        return response()->json($client);
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
        $client = $this->launcherClient::findOrFail($request->route('launcher_client'));
        $client->delete();

        return response()->json([], 204);
    }
}
