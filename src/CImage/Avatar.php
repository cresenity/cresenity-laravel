<?php

namespace Cresenity\Laravel\CImage;

class Avatar
{
    private $engineName;

    public function __construct($engineName = 'Initials')
    {
        $this->engineName = $engineName;
    }

    /**
     * @return CImage_Avatar_Api_Initials
     */
    public function api()
    {
        $className = 'CImage_Avatar_Api_' . $this->engineName;

        return new $className();
    }

    /**
     * @return CImage_Avatar_Api_Initials
     */
    public function createInitials()
    {
        return new CImage_Avatar_Api_Initials();
    }
}
