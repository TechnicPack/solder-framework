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
use Znck\Eloquent\Traits\BelongsToThrough;

class Dependency extends Model
{
    use BelongsToThrough;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'build_id',
        'version_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'build_id',
        'version_id',
    ];

    /**
     * Modpack.
     *
     * @throws \Exception
     *
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function modpack()
    {
        return $this->belongsToThrough(config('solder.model.modpack'), config('solder.model.build'));
    }

    /**
     * Modpack Build.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function build()
    {
        return $this->belongsTo(config('solder.model.build'));
    }

    /**
     * Mod.
     *
     * @throws \Exception
     *
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function mod()
    {
        return $this->belongsToThrough(config('solder.model.mod'), config('solder.model.version'));
    }

    /**
     * Mod Version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function version()
    {
        return $this->belongsTo(config('solder.model.version'));
    }
}
