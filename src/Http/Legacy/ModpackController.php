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
use TechnicPack\SolderFramework\Modpack;
use Illuminate\Http\Resources\Json\Resource;
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
     * The TechnicKey model.
     *
     * @var \TechnicPack\SolderFramework\TechnicKey
     */
    protected $key;

    /**
     * The TechnicClient model.
     *
     * @var \TechnicPack\SolderFramework\TechnicClient
     */
    protected $client;

    /**
     * ModpackController constructor.
     */
    public function __construct()
    {
        Resource::withoutWrapping();
        $this->middleware('api');
        $this->modpack = config('solder.model.modpack');
        $this->key = config('solder.model.technicKey');
        $this->client = config('solder.model.technicClient');
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
        $modpacks = $this->modpack::with('builds', 'clients')->get();

        $modpacks = $modpacks->filter(function ($modpack) use ($request) {
            return $this->authorizeModpack($modpack, $request);
        });

        $modpacks = $modpacks->map(function ($modpack) use ($request) {
            $builds = $modpack->builds->filter(function ($build) use ($modpack, $request) {
                return $this->authorizeBuild($build, $modpack, $request);
            });

            return $modpack->setRelation('builds', $builds);
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

        if (! $this->authorizeModpack($modpack, $request)) {
            throw new ModelNotFoundException();
        }

        $modpack->builds = $modpack->builds->filter(function ($build) use ($modpack, $request) {
            return $this->authorizeBuild($build, $modpack, $request);
        });

        return new ModpackResource($modpack);
    }

    /**
     * Check if the request is authorized to show the modpack.
     *
     * @param $modpack
     * @param Request $request
     *
     * @return bool
     */
    private function authorizeModpack($modpack, Request $request)
    {
        $modpackIsHidden = 'hidden' === $modpack->visibility;
        $modpackIsPrivate = 'private' === $modpack->visibility;
        $modpackIsPublic = 'public' === $modpack->visibility;
        $requestHasValidApiKey = $this->key::isValid($request->get('k'));
        $requestHasAuthorizedClient = $modpack->clients->pluck('token')->contains($request->get('cid'));

        if ($modpackIsHidden) {
            return false;
        }

        if ($modpackIsPublic || $requestHasValidApiKey) {
            return true;
        }

        if ($modpackIsPrivate && $requestHasAuthorizedClient) {
            return true;
        }

        return false;
    }

    /**
     * Check if the request is authorized to show the modpack.
     *
     * @param $build
     * @param $modpack
     * @param Request $request
     *
     * @return bool
     */
    private function authorizeBuild($build, $modpack, Request $request)
    {
        $buildIsHidden = 'hidden' === $build->visibility;
        $buildIsPrivate = 'private' === $build->visibility;
        $buildIsPublic = 'public' === $build->visibility;
        $requestHasValidApiKey = $this->key::isValid($request->get('k'));
        $requestHasAuthorizedClient = $modpack->clients->pluck('token')->contains($request->get('cid'));

        if ($buildIsHidden) {
            return false;
        }

        if ($buildIsPublic || $requestHasValidApiKey) {
            return true;
        }

        if ($buildIsPrivate && $requestHasAuthorizedClient) {
            return true;
        }

        return false;
    }
}
