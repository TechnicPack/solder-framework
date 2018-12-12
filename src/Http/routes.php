<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// API Root ...
Route::get('/', 'ServiceController@show')->name('root');

// Legacy API ...
Route::get('/verify/{token}', '\\TechnicPack\\SolderFramework\\Http\\Legacy\\KeyController@show');
Route::get('/modpack', '\\TechnicPack\\SolderFramework\\Http\\Legacy\\ModpackController@index');
Route::get('/modpack/{modpack}', '\\TechnicPack\\SolderFramework\\Http\\Legacy\\ModpackController@show');
Route::get('/modpack/{modpack}/{build}', '\\TechnicPack\\SolderFramework\\Http\\Legacy\\ModpackBuildController@show');

// Modpack Routes ...
Route::match(['post', 'put', 'patch'], '/modpacks/{modpack}/icon', 'ModpackIconController@store')->name('modpacks.icon.store');
Route::delete('/modpacks/{modpack}/icon', 'ModpackIconController@destroy')->name('modpacks.icon.destroy');
Route::apiResource('modpacks', 'ModpackController');

// Build Routes ..
Route::match(['post', 'put', 'patch'], '/modpacks/{modpack}/builds/latest', 'ModpackLatestBuildController@update')->name('modpacks.builds.latest.update');
Route::match(['post', 'put', 'patch'], '/modpacks/{modpack}/builds/recommended', 'ModpackRecommendedBuildController@update')->name('modpacks.builds.recommended.update');
Route::apiResource('modpacks.builds', 'ModpackBuildController');

// Mod Routes ..
Route::apiResource('mods', 'ModController');

// Version Routes ..
Route::apiResource('mods.versions', 'ModVersionController');
Route::post('/mods/{mod}/versions/{version}/package', 'PackageController@store')->name('mods.versions.package.store');
Route::delete('/mods/{mod}/versions/{version}/package', 'PackageController@destroy')->name('mods.versions.package.destroy');

// Dependency Routes ..
Route::apiResource('dependencies', 'DependencyController')->only(['store', 'destroy']);

// Platform Key Routes ..
Route::apiResource('platform-keys', 'PlatformKeyController');

// Launcher Client Routes ..
Route::apiResource('launcher-clients', 'LauncherClientController');

// Modpack Authorized Client Routes ..
Route::match(['post', 'put', 'patch'], 'authorized-clients', 'AuthorizedClientsController@sync');
