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

class Solder
{
    /**
     * The string prefix for all solder api routes.
     *
     * @var string
     */
    public static $apiRoutePrefix = 'api';

    /**
     * Set the uri to prefix api routes with.
     *
     * @param $prefix
     *
     * @return Solder
     */
    public static function prefixApiRoutesWith($prefix)
    {
        self::$apiRoutePrefix = $prefix;

        return new static();
    }
}
