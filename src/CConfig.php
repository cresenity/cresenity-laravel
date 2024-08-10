<?php
namespace Cresenity\Laravel;

class CConfig
{
    /**
     * @return \Illuminate\Config\Repository
     */
    public static function repository()
    {
        return \c::container('config');
    }
}
