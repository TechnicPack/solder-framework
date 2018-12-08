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
use Illuminate\Routing\Controller as BaseController;

class KeyController extends BaseController
{
    /**
     * The key model.
     *
     * @var \TechnicPack\SolderFramework\Key
     */
    private $key;

    /**
     * KeyController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->key = config('solder.model.key');
    }

    /**
     * show/verify the given Key.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $key = $this->key::where('token', $request->route('token'))->first();

        if (empty($key)) {
            return response()->json([
                'error' => 'Invalid key provided.',
            ]);
        }

        return response()->json([
            'valid'      => 'Key validated.',
            'name'       => $key->name,
            'created_at' => $key->created_at->toDateTimeString(),
        ]);
    }
}
