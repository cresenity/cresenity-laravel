<?php
namespace Cresenity\Laravel\CPeriod\Exception;

use Cresenity\Laravel\CPeriod\Exception as CPeriodException;

class NonMutableOffsetsException extends CPeriodException
{
    public static function forClass($className)
    {
        return new self("Offsets of `{$className}` objects are not mutable.");
    }
}
