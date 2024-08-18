<?php
namespace Cresenity\Laravel\CPeriod\Exception;

use Cresenity\Laravel\CPeriod\Exception as CPeriodException;

class InvalidDateTimeClassException extends CPeriodException
{
    public static function forString($string)
    {
        return new self("The string `{$string}` isn't a valid class implementing DateTimeInterface.");
    }
}
