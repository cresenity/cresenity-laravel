<?php
namespace Cresenity\Laravel;

class CFile
{
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([app('files'),$name], $arguments);
    }
}
