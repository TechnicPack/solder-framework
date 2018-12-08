<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Http\Legacy;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
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
        Resource::withoutWrapping();
        $this->middleware('api');
        $this->modpack = config('solder.model.modpack');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return BuildResource
     */
    public function show(Request $request)
    {
        $build = $this->modpack::where('slug', $request->modpack)
            ->firstOrFail()
            ->builds()
            ->where('tag', $request->build)
            ->with('dependencies.mod', 'dependencies.version')
            ->firstOrFail();

        return new BuildResource($build);
    }
}
