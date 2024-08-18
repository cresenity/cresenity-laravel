<?php

namespace Cresenity\Laravel\CImage\Avatar;

class EngineFactory
{
    public static function create($engineName)
    {
        $className = '\\Cresenity\\Laravel\\CImage\\Avatar\\Engine\\' . $engineName;
        return new $className();
    }
}
