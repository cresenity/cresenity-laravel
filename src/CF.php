<?php
namespace Cresenity\Laravel;

final class CF
{
    /**
     * Chartset used for this application.
     *
     * @var string
     */
    public static $charset = 'utf-8';
    /**
     * Check if CF is run under testing.
     *
     * @return bool
     */
    public static function isTesting()
    {
        if (defined('CFTesting')
            || (is_array($_SERVER) && isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'testing')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get a config item or group.
     *
     * @param mixed      $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public static function config($key, $default = null)
    {
        return \c::config($key, $default);
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public static function getLocale()
    {
        return app()->getLocale();
    }

    public static function version() {
        return '1.0';
    }

    /**
     * Get the current application charset.
     *
     * @return string
     */
    public static function getCharset() {
        return static::$charset;
    }
}
