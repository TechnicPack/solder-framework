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
use Illuminate\Routing\Controller as BaseController;

class ModpackIconController extends BaseController
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $modpack = $this->modpack::findOrFail($request->modpack);

        $request->validate([
            'icon' => [
                'required',
                'image',
                'max:5000',
                Rule::dimensions()->maxWidth(500)->maxHeight(500),
            ],
        ]);

        $modpack->unsetIcon();
        $modpack->icon_path = $request->file('icon')->store('icons', config('solder.disk.icons'));
        $modpack->save();

        return response([], 204);
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
        $modpack = $this->modpack::findOrFail($request->modpack);
        $modpack->unsetIcon();

        return response([], 204);
    }
}
