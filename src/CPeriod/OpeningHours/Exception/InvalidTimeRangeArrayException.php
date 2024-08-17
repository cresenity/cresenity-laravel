<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours\Exception;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception as OpeningHoursException;

class InvalidTimeRangeArrayException extends OpeningHoursException
{
    public static function create()
    {
        return new self('TimeRange array definition must at least contains an "hours" property.');
    }
}
