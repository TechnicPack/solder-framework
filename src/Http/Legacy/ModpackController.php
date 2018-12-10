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
use TechnicPack\SolderFramework\Enums\Status;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModpackController extends BaseController
{
    /**
     * The modpack model.
     *
     * @var \TechnicPack\SolderFramework\Modpack
     */
    protected $modpack;

    /**
     * The modpack model.
     *
     * @var \TechnicPack\SolderFramework\Modpack
     */
    protected $key;

    /**
     * ModpackController constructor.
     */
    public function __construct()
    {
        Resource::withoutWrapping();
        $this->middleware('api');
        $this->modpack = config('solder.model.modpack');
        $this->key = config('solder.model.key');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return ModpacksCollection
     */
    public function index(Request $request)
    {
        $modpacks = $this->modpack::with('builds')->get();

        $modpacks = $modpacks->filter(function ($modpack) use ($request) {
            return $this->authorizeList($modpack, $request);
        });

        return new ModpacksCollection($modpacks);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return ModpackResource
     */
    public function show(Request $request)
    {
        $modpack = $this->modpack::where('slug', $request->modpack)
            ->with('builds')
            ->firstOrFail();

        if (! $this->authorizeShow($modpack, $request)) {
            throw new ModelNotFoundException();
        }

        return new ModpackResource($modpack);
    }

    /**
     * Check if the request is authorized to list the modpack.
     *
     * @param $modpack
     * @param Request $request
     *
     * @return bool
     */
    private function authorizeList($modpack, Request $request)
    {
        if (Status::public === $modpack->status) {
            return true;
        }

        if ($this->key::isValid($request->get('k'))) {
            return true;
        }

        return false;
    }

    /**
     * Check if the request is authorized to show the modpack.
     *
     * @param $modpack
     * @param Request $request
     *
     * @return bool
     */
    private function authorizeShow($modpack, Request $request)
    {
        if (Status::public === $modpack->status || Status::unlisted === $modpack->status) {
            return true;
        }

        if ($this->key::isValid($request->get('k'))) {
            return true;
        }

        return false;
    }
}
