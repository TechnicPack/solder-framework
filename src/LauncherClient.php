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

class LauncherClient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'name',
    ];

    /**
     * Check if the given token is valid.
     *
     * @param $token
     *
     * @return mixed
     */
    public static function isValid($token)
    {
        return self::where('token', $token)->exists();
    }
}
