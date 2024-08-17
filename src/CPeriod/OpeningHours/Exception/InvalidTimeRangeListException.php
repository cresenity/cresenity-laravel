<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours\Exception;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception as OpeningHoursException;

class InvalidTimeRangeListException extends OpeningHoursException
{
    public static function create()
    {
        return new self('The given list is not a valid list of TimeRange instance containing at least one range.');
    }
}
