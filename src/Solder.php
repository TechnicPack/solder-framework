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
     * The string prefix for all solder spa routes.
     *
     * @var string
     */
    public static $appRoutePrefix = 'app';

    /**
     * The blade template to use for rendering the vue app.
     *
     * @var string
     */
    public static $appBladeTemplate = 'solder::app';

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

    /**
     * Set the uri to prefix spa routes with.
     *
     * @param $prefix
     *
     * @return Solder
     */
    public static function prefixAppRoutesWith($prefix)
    {
        self::$appRoutePrefix = $prefix;

        return new static();
    }

    /**
     * Set the view template to render the solder application in.
     *
     * @param $view
     *
     * @return Solder
     */
    public static function useView($view)
    {
        self::$appBladeTemplate = $view;

        return new static();
    }

    /**
     * Get the current Solder version.
     *
     * @return string
     */
    public static function version()
    {
        return '0.9.0';
    }
}
