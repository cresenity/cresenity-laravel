<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours\Exception;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception as OpeningHoursException;

class InvalidTimeRangeStringException extends OpeningHoursException
{
    public static function forString($string)
    {
        return new self("The string `{$string}` isn't a valid time range string. A time string must be a formatted as `H:i-H:i`, e.g. `09:00-18:00`.");
    }
}
