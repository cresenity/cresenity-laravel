<?php
namespace Cresenity\Laravel\CPeriod\Exception;

use Cresenity\Laravel\CPeriod\Exception as CPeriodException;

class CannotComparePeriodException extends CPeriodException
{
    /**
     * @return CannotComparePeriodException
     */
    public static function precisionDoesNotMatch()
    {
        return new self("Cannot compare two periods whose precision doesn't match.");
    }
}
