<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string name
 * @property string modid
 */
class Mod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'modid',
        'author',
        'url',
        'description',
    ];

    /**
     * Mod Versions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions()
    {
        return $this->hasMany(config('solder.model.version'));
    }

    /**
     * Limit mod list to items not already part of a build.
     *
     * @param Builder $query
     * @param $buildId
     */
    public function scopeNotInBuild(Builder $query, $buildId)
    {
        $query
            ->whereNotExists(function ($query) use ($buildId) {
                $query->select(DB::raw(1))
                    ->from('dependencies')
                    ->join('versions', 'versions.id', 'dependencies.version_id')
                    ->whereRaw("dependencies.build_id = {$buildId}")
                    ->whereRaw('versions.mod_id = mods.id');
            });
    }
}
