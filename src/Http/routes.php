<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Modpack Routes ...
Route::match(['post', 'put', 'patch'], '/modpacks/{modpack}/icon', 'ModpackIconController@store')->name('modpacks.icon.store');
Route::delete('/modpacks/{modpack}/icon', 'ModpackIconController@destroy')->name('modpacks.icon.destroy');
Route::apiResource('modpacks', 'ModpackController');

// Build Routes ..
Route::apiResource('modpacks.builds', 'ModpackBuildController');
