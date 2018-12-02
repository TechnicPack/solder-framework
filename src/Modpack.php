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

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string name
 * @property string slug
 * @property string icon_path
 * @property string icon
 * @property Collection builds
 */
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
        'url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'icon_path',
        'latest_id',
        'recommended_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'icon',
    ];

    /**
     * The storage disk for icons.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $iconStorage;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->iconStorage = Storage::disk(config('solder.disk.icons'));
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        self::deleting(function (self $modpack) {
            $modpack->unsetIcon();
            $modpack->builds->each->delete();
        });

        parent::boot();
    }

    /**
     * Modpack builds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function builds()
    {
        return $this->hasMany(Build::class);
    }

    /**
     * The latest modpack build.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function latest()
    {
        return $this->belongsTo(Build::class);
    }

    /**
     * The latest modpack build.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recommended()
    {
        return $this->belongsTo(Build::class);
    }

    /**
     * Get the full URL for the modpack icon.
     *
     * @return string
     */
    public function getIconAttribute()
    {
        if (! $this->icon_path) {
            $hash = md5($this->name);

            return "https://www.gravatar.com/avatar/{$hash}?s=50&d=identicon";
        }

        return $this->iconStorage->url($this->icon_path);
    }

    /**
     * Remove icon from storage and database.
     */
    public function unsetIcon()
    {
        $this->iconStorage->delete($this->icon_path);
        $this->icon_path = null;
        $this->save();
    }
}
