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
use TechnicPack\SolderFramework\Rules\UrlSafe;
use Illuminate\Routing\Controller as BaseController;

class ModpackBuildController extends BaseController
{
    /**
     * The build model.
     *
     * @var \TechnicPack\SolderFramework\Build
     */
    protected $build;
    /**
     * The modpack model.
     *
     * @var \TechnicPack\SolderFramework\Modpack
     */
    protected $modpack;

    /**
     * BuildController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->build = config('solder.model.build');
        $this->modpack = config('solder.model.modpack');
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
        $builds = QueryBuilder::for($this->build)
            ->whereModpack($request->modpack)
            ->get();

        return response()->json($builds);
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
        $modpack = $this->modpack::findOrFail($request->modpack);
        $unique = Rule::unique('builds')->where('modpack_id', $modpack->id);

        $attributes = $request->validate([
            'tag'               => ['required', new UrlSafe(), $unique],
            'minecraft_version' => ['required'],
            'forge_version'     => ['nullable'],
            'java_version'      => ['nullable'],
            'java_memory'       => ['nullable', 'int'],
        ]);

        $build = $modpack->builds()->create($attributes);

        return response()->json($build, 201);
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
        $build = QueryBuilder::for($this->build)
            ->findOrFail($request->build);

        return response()->json($build);
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
        $build = $this->build::whereModpack($request->modpack)
            ->findOrFail($request->build);

        $unique = Rule::unique('builds')
            ->where('modpack_id', $request->modpack)
            ->ignore($build->id);

        $attributes = $request->validate([
            'tag'               => ['required', new UrlSafe(), $unique],
            'minecraft_version' => ['required'],
            'java_version'      => ['nullable'],
            'java_memory'       => ['int', 'nullable'],
        ]);

        $build->update($attributes);

        return response()->json($build);
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
        $build = $this->build::findOrFail($request->build);
        $build->delete();

        return response()->json([], 204);
    }
}
