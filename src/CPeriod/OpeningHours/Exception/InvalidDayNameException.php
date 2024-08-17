<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours\Exception;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception as OpeningHoursException;

class InvalidDayNameException extends OpeningHoursException
{
    /**
     * @param string $name
     *
     * @return self
     */
    public static function invalidDayName($name)
    {
        return new self("Day `{$name}` isn't a valid day name. Valid day names are lowercase english words, e.g. `monday`, `thursday`.");
    }
}
