<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours\Exception;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception as OpeningHoursException;

class OverlappingTimeRangesException extends OpeningHoursException
{
    public static function forRanges($rangeA, $rangeB)
    {
        return new self("Time ranges {$rangeA} and {$rangeB} overlap.");
    }
}
