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

Route::prefix('api')->name('api.')->group(function () {

    // Modpack Routes ...
    Route::post('/modpacks/{modpack}/icon', 'TechnicPack\SolderFramework\Http\Controllers\ModpackIconController@store')->name('modpacks.icon.store');
    Route::delete('/modpacks/{modpack}/icon', 'TechnicPack\SolderFramework\Http\Controllers\ModpackIconController@destroy')->name('modpacks.icon.destroy');
    Route::apiResource('modpacks', ModpackController::class);
});
