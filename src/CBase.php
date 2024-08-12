<?php
namespace Cresenity\Laravel;

use Cresenity\Laravel\CBase\ForwarderStaticClass;
use Cresenity\Laravel\CBase\StringParamable;

class CBase
{
    const ENVIRONMENT_PRODUCTION = 'production';

    const ENVIRONMENT_DEVELOPMENT = 'development';

    const ENVIRONMENT_STAGING = 'staging';

    const ENVIRONMENT_LOCAL = 'local';

    const ENVIRONMENT_TESTING = 'testing';

    /**
     * @var array
     */
    protected static $originalPost;

    /**
     * @var array
     */
    protected static $originalGet;

    /**
     * @var array
     */
    protected static $originalFiles;

    /**
     * CF Session.
     *
     * @var CSession_Store
     */
    private static $session;

    public static function createRecursionContext()
    {
        return new CBase_RecursionContext();
    }
    /**
     * @param string $string
     * @param array $params
     *
     * @return \Cresenity\Laravel\CBase\StringParamable
     */
    public static function createStringParamable($string, array $params = [])
    {
        return new StringParamable($string, $params);
    }

    public static function session()
    {
        if (static::$session == null && CSession::sessionConfigured()) {
            $request = CHTTP::request();
            CSession::manager()->applyNativeSession();

            static::$session = \c::tap(CSession::manager()->createStore(), function ($session) use ($request) {
                $session->setId($request->cookies->get($session->getName()));
                $session->setRequestOnHandler($request);
                $session->start();
            });
        }

        return static::$session;
    }

    /**
     * @param string $class
     *
     * @return \Cresenity\Laravel\CBase\ForwarderStaticClass
     */
    public static function forwarderStaticClass($class)
    {
        return new ForwarderStaticClass($class);
    }

    /**
     * Called for CBootstrap boot before app boot.
     *
     * @return void
     */
    public static function boot()
    {
        static::$originalPost = $_POST;
        static::$originalGet = $_GET;
        static::$originalFiles = $_FILES;
    }

    public static function originalGetData()
    {
        return static::$originalGet;
    }

    public static function originalPostData()
    {
        return static::$originalPost;
    }

    public static function originalFilesData()
    {
        return static::$originalPost;
    }
}
