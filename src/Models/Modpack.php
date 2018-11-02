<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Modpack extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'icon',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'icon_url',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        self::deleting(function ($modpack) {
            Storage::delete($modpack->icon);
        });

        parent::boot();
    }

    /**
     * Get the full URL for the modpack icon.
     *
     * @return string
     */
    public function getIconUrlAttribute()
    {
        return Storage::url($this->icon);
    }
}
