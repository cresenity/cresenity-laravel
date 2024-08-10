<?php
namespace Cresenity\Laravel;

class Config
{
    /**
     * @return \Illuminate\Config\Repository
     */
    public static function repository()
    {
        return \c::container('config');
    }
}
