<?php
namespace Cresenity\Laravel\CPeriod\Traits;

use Cresenity\Laravel\CPeriod\Factory;

trait FactoryTrait
{
    /**
     * @param \DateTimeInterface|string $start
     * @param \DateTimeInterface|string $end
     * @param null|\Cresenity\Laravel\CPeriod\Precision   $precision
     * @param null|\Cresenity\Laravel\CPeriod\Boundaries  $boundaries
     * @param null|string              $format
     *
     * @return static
     */
    public static function make(
        $start,
        $end,
        $precision = null,
        $boundaries = null,
        $format = null
    ) {
        return Factory::make(
            $start,
            $end,
            $precision,
            $boundaries,
            $format
        );
    }

    /**
     * @param string $string
     *
     * @return static
     */
    public static function fromString($string)
    {
        return Factory::fromString($string);
    }
}
