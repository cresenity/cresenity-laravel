<?php
namespace Cresenity\Laravel\CPeriod\Exception;

use Cresenity\Laravel\CPeriod\Exception as CPeriodException;

/**
 * @author Hery Kurniawan
 */
class InvalidPeriodException extends CPeriodException
{
    public static function startDateCannotBeAfterEndDate(\DateTime $startDate, \DateTime $endDate)
    {
        return new static("Start date `{$startDate->format('Y-m-d')}` cannot be after end date `{$endDate->format('Y-m-d')}`.");
    }
}
