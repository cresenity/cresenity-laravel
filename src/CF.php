<?php
namespace Cresenity\Laravel;

final class CF
{

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
    * @param mixed      $required
    *
    * @return mixed
    */
    public static function config($key, $default = null)
    {
        return \c::config($key, $default);
    }
}
