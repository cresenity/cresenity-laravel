<?php
namespace Cresenity\Laravel;

use Cresenity\Laravel\Manager\Theme;

final class Manager
{
    private static $instance;

    protected static $theme;

    /**
    * @return \Cresenity\Laravel\Manager
    */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @return \Cresenity\Laravel\Manager\Theme
     */
    public static function theme()
    {
        if (self::$theme == null) {
            self::$theme = new Theme();
        }

        return self::$theme;
    }
}
