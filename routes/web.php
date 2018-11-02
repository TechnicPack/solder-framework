<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Route;
use TechnicPack\SolderFramework\Http\Controllers\ModpackController;

Route::prefix('api')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'status' => 'ok',
        ]);
    });

    // Modpack Routes ...
    Route::apiResource('modpacks', ModpackController::class);
});
