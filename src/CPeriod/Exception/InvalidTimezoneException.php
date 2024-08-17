<?php
namespace Cresenity\Laravel\CPeriod\Exception;

class InvalidTimezoneException extends \InvalidArgumentException
{
    /**
     * @return self
     */
    public static function create()
    {
        return new self('Invalid Timezone');
    }
}
