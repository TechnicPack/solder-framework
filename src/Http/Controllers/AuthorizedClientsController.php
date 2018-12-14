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

class AuthorizedClientsController extends BaseController
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
        $this->middleware('auth:api');
        $this->modpack = config('solder.model.modpack');
    }

    /**
     * Sync the resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request)
    {
        // FIXME: exists checks are naive, needs to be eloquent based
        $request->validate([
            'modpack_id'   => ['required', 'exists:modpacks,id'],
            'clients'      => ['nullable', 'array'],
            'clients.*'    => ['exists:technic_clients,id'],
        ]);

        $modpack = $this->modpack::findOrFail($request->get('modpack_id'));
        $modpack->clients()->sync($request->get('clients'));

        return response()->json($modpack->clients, 200);
    }
}
