<?php
namespace Cresenity\Laravel\CPeriod\Exception;

/**
 * @author Hery Kurniawan
 */
class InvalidDateException extends \InvalidArgumentException
{
    /**
     * @param string $parameter
     *
     * @return InvalidDateException
     */
    public static function cannotBeNull($parameter)
    {
        return new static("{$parameter} cannot be null");
    }

    /**
     * @param string      $date
     * @param null|string $format
     *
     * @return InvalidDateException
     */
    public static function forFormat($date, $format)
    {
        $message = "Could not construct a date from `{$date}`";

        if ($format) {
            $message .= " with format `{$format}`";
        }

        return new static($message);
    }
}
