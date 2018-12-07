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

use Emgag\Flysystem\Hash\HashPlugin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string package
 * @property string package_name
 * @property int package_size
 * @property string package_hash
 */
class Version extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'package_url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'package_size' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'mod_id',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        self::deleting(function (self $version) {
            $version->dependencies->each->delete();
        });

        parent::boot();
    }

    /**
     * Parent mod.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mod()
    {
        return $this->belongsTo(config('solder.model.mod'));
    }

    /**
     * Related dependencies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dependencies()
    {
        return $this->hasMany(config('solder.model.dependency'));
    }

    /**
     * Package URL.
     *
     * @return mixed
     */
    public function getPackageUrlAttribute()
    {
        return optional($this->package, function ($path) {
            return $this->storage()->url($path);
        });
    }

    /**
     * Scope a query to only include versions for the given mod.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|Model                             $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereMod($query, $id)
    {
        if ($id instanceof Model) {
            $id = $id->getKey();
        }

        return $query->where('mod_id', '=', $id);
    }

    /**
     * Persist the package in storage.
     *
     * @param \Illuminate\Http\File|\Illuminate\Http\UploadedFile $package
     *
     * @return bool
     */
    public function setPackage($package)
    {
        if (null === $package) {
            return $this->unsetPackage();
        }

        $this->package = $this->storage()->putFile('files', $package);
        $this->package_name = $package->getClientOriginalName();
        $this->package_size = $package->getSize();
        $this->package_hash = $package->getHash();

        return $this->save();
    }

    /**
     * Remove the package from storage.
     *
     * @return bool
     */
    private function unsetPackage()
    {
        $this->storage()->delete($this->package);

        $this->package = null;
        $this->package_name = null;
        $this->package_size = null;
        $this->package_hash = null;

        return $this->save();
    }

    public function refreshPackageMeta()
    {
        $this->package_hash = $this->storage()->hash($this->package, 'md5');
        $this->package_size = $this->storage()->size($this->package);

        return $this->save();
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    private function storage()
    {
        $filesystem = Storage::disk(config('solder.disk.files'));
        $filesystem->addPlugin(new HashPlugin());

        return $filesystem;
    }
}
