<?php
namespace Cresenity\Laravel;

class CLaravel
{
    public static function resourcesPath()
    {
        return \realpath(dirname(__FILE__).'/../resources');
    }

    public static function publicPath()
    {
        return \realpath(dirname(__FILE__).'/../public');
    }

    public static function publicJsPath($js = null)
    {
        $path =  self::publicPath().DS.'/js';
        if ($js) {
            $path.='/'.$js;
        }
        return $path;
    }
    public static function publicCssPath($css = null)
    {
        $path =  self::publicPath().DS.'/css';
        if ($css) {
            $path.='/'.$css;
        }
        return $path;
    }

    public static function basePath() {
        return app()->basePath();
    }
}
