<?php
namespace Cresenity\Laravel;

use c;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class CAuth
{
    /**
     * Get Manager instance.
     *
     * @return \Illuminate\Auth\AuthManager
     */
    public static function manager()
    {
        return c::container('auth');
    }

    public static function guard()
    {
        return self::manager()->guard();
    }

    public static function gate()
    {
        return c::container(GateContract::class);
    }

    /**
     * @return CAuth_ImpersonateManager
     */
    public static function impersonateManager()
    {
        return CAuth_ImpersonateManager::instance();
    }
}
