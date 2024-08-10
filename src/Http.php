<?php
namespace Cresenity\Laravel;

use \Illuminate\Http\Response;
use \Illuminate\Http\Request;

class Http
{


    /**
     * @var \Illuminate\Http\Request
     */
    protected static $request;
    /**
     * @param string $content
     * @param int    $status
     *
     * @return \Illuminate\Http\Response
     */
    public static function createResponse($content = '', $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }


    /**
     * @return \Illuminate\Http\Request
     */
    public static function request()
    {
        if (self::$request == null) {
            self::$request = Request::capture();
        }

        return self::$request;
    }
}
