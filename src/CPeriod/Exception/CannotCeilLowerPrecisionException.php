<?php
namespace Cresenity\Laravel\CPeriod\Exception;

use Cresenity\Laravel\CPeriod\Exception as CPeriodException;
use Cresenity\Laravel\CPeriod\Precision;

class CannotCeilLowerPrecisionException extends CPeriodException
{
    /**
     * @param \Cresenity\Laravel\CPeriod\Precision $a
     * @param \Cresenity\Laravel\CPeriod\Precision $b
     *
     * @return CannotCeilLowerPrecisionException
     */
    public static function precisionIsLower(Precision $a, Precision $b)
    {
        $from = self::unitName($a);
        $to = self::unitName($b);

        return new self("Cannot get the latest ${from} of a ${to}.");
    }

    protected static function unitName(Precision $precision)
    {
        $matchMap = [
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        return \carr::get($matchMap, $precision->intervalName());
    }
}
