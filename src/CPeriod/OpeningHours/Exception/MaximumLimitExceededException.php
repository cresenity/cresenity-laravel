<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours\Exception;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception as OpeningHoursException;

class MaximumLimitExceededException extends OpeningHoursException
{
    /**
     * @param string $string
     *
     * @return self
     */
    public static function forString($string)
    {
        return new self($string);
    }
}
