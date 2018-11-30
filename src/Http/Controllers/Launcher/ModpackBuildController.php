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

class ModpackBuildController extends BaseController
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
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $build = $this->modpack::where('slug', $request->modpack)
            ->firstOrFail()
            ->builds()
            ->where('tag', $request->build)
            ->firstOrFail();

        return response()->json([
            'minecraft' => $build->minecraft_version,
            'java'      => $build->java_version,
            'memory'    => $build->java_memory,
            'mods'      => [],
        ]);
    }
}
