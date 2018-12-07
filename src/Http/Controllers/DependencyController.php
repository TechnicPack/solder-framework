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
use Illuminate\Support\Facades\DB;
use TechnicPack\SolderFramework\Version;
use Illuminate\Routing\Controller as BaseController;

class DependencyController extends BaseController
{
    /**
     * The dependency model.
     *
     * @var \TechnicPack\SolderFramework\Dependency
     */
    protected $dependency;

    /**
     * DependencyController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
        $this->dependency = config('solder.model.dependency');
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
        //FIXME: the exists rule is naive, need an eloquent based exists rule
        $attributes = $request->validate([
            'build_id'   => [
                'required',
                Rule::exists('builds', 'id'),
            ],
            'version_id' => [
                'required',
                Rule::exists('versions', 'id'),
                Rule::notIn(Version::whereExists(function ($query) use ($request) {
                    $buildId = $request->get('build_id') ?? 0;

                    $query->select(DB::raw(1))
                        ->from('dependencies')
                        ->join('versions', 'versions.id', 'dependencies.version_id')
                        ->whereRaw('dependencies.build_id = '.$buildId)
                        ->whereRaw('dependencies.version_id = versions.id');
                })->pluck('id')->all()),
            ],
        ]);

        $dependency = $this->dependency::create($attributes);

        return response()->json($dependency, 201);
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
        $dependency = $this->dependency::findOrFail($request->route('dependency'));
        $dependency->delete();

        return response()->json([], 204);
    }
}
