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

use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag',
        'minecraft_version',
        'forge_version',
        'java_version',
        'java_memory',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'modpack_id',
    ];

    /**
     * Parent modpack.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modpack()
    {
        return $this->belongsTo(Modpack::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|Model                             $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereModpack($query, $id)
    {
        if ($id instanceof Model) {
            $id = $id->getKey();
        }

        return $query->where('modpack_id', '=', $id);
    }
}
