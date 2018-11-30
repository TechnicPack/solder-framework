<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Http\Controllers\Launcher;

use Illuminate\Http\Request;
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
        $this->modpack = config('solder.model.modpack');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modpacks = $this->modpack::with('builds')->get()
            ->keyBy('slug')
            ->map(function ($modpack) use ($request) {
                if ('full' === $request->query('include')) {
                    return $this->fullResource($modpack);
                }

                return $modpack->name;
            });

        return response()->json([
            'modpacks'   => $modpacks,
            'mirror_url' => null,
        ]);
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
        $modpack = $this->modpack::where('slug', $request->modpack)
            ->with('builds')
            ->firstOrFail();

        return response()->json($this->fullResource($modpack));
    }

    /**
     * @param $modpack
     *
     * @return array
     */
    private function fullResource($modpack): array
    {
        return [
            'name'         => $modpack->slug,
            'display_name' => $modpack->name,
            'url'          => null,
            'icon'         => $modpack->icon,
            'recommended'  => null,
            'latest'       => null,
            'builds'       => $modpack->builds->pluck('tag'),
        ];
    }
}
