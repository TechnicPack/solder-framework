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
use Illuminate\Support\Facades\Storage;
use TechnicPack\SolderFramework\Models\Modpack;
use Illuminate\Routing\Controller as BaseController;

class ModpackIconController extends BaseController
{
    /**
     * ModpackController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth:api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Modpack                  $modpack
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Modpack $modpack)
    {
        $request->validate([
            'icon' => [
                'required',
                'image',
                'max:5000',
                Rule::dimensions()->maxWidth(500)->maxHeight(500),
            ],
        ]);

        if (null !== $modpack->icon) {
            Storage::delete($modpack->icon);
        }

        $modpack->icon = $request->file('icon')->store('icons');
        $modpack->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Modpack $modpack
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modpack $modpack)
    {
        Storage::delete($modpack->icon);

        $modpack->icon = null;
        $modpack->save();

        return response()->json([], 204);
    }
}
