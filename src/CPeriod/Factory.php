<?php
namespace Cresenity\Laravel\CPeriod;

use Cresenity\Laravel\CPeriod;
use Cresenity\Laravel\CPeriod\Exception\InvalidDateException;
use Illuminate\Support\Str;

class Factory
{
    /**
     * @param string $string
     *
     * @return \Cresenity\Laravel\CPeriod
     */
    public static function fromString($string)
    {
        preg_match('/(\[|\()([\d\-\s\:]+)[,]+([\d\-\s\:]+)(\]|\))/', $string, $matches);

        list(1 => $startBoundary, 2 => $startDate, 3 => $endDate, 4 => $endBoundary) = $matches;

        $boundaries = Boundaries::fromString($startBoundary, $endBoundary);

        $startDate = trim($startDate);

        $endDate = trim($endDate);

        $precision = Precision::fromString($startDate);

        $start = self::resolveDate($startDate, $precision->dateFormat());

        $end = self::resolveDate($endDate, $precision->dateFormat());

        return new CPeriod(
            $start,
            $end,
            $precision,
            $boundaries,
        );
    }

    /**
     * @param \DateTimeInterface|string $start
     * @param \DateTimeInterface|string $end
     * @param null|Precision           $precision
     * @param null|Boundaries          $boundaries
     * @param null|string              $format
     *
     * @return CPeriod
     */
    public static function make(
        $start,
        $end,
        $precision = null,
        $boundaries = null,
        $format = null
    ) {
        $boundaries = $boundaries ?: Boundaries::EXCLUDE_NONE();
        $precision = $precision ?: Precision::DAY();
        $start = $precision->roundDate(self::resolveDate($start, $format));
        $end = $precision->roundDate(self::resolveDate($end, $format));

        $period = new CPeriod($start, $end, $precision, $boundaries);

        return $period;
    }

    /**
     * @param \DateTimeImmutable  $includedStart
     * @param \DateTimeImmutable  $includedEnd
     * @param \Cresenity\Laravel\CPeriod\Precision  $precision
     * @param \Cresenity\Laravel\CPeriod\Boundaries $boundaries
     *
     * @return \Cresenity\Laravel\CPeriod
     */
    public static function makeWithBoundaries(
        $includedStart,
        $includedEnd,
        $precision,
        $boundaries
    ) {
        $includedStart = $precision->roundDate(self::resolveDate($includedStart));
        $includedEnd = $precision->roundDate(self::resolveDate($includedEnd));

        $period = new CPeriod(
            $boundaries->realStart($includedStart, $precision),
            $boundaries->realEnd($includedEnd, $precision),
            $precision,
            $boundaries,
        );

        return $period;
    }

    /**
     * @param \DateTimeInterface|string $date
     * @param null|string              $format
     *
     * @return \DateTimeImmutable
     */
    protected static function resolveDate(
        $date,
        $format = null
    ) {
        if ($date instanceof \DateTimeImmutable) {
            return $date;
        }

        if ($date instanceof \DateTime) {
            return \DateTimeImmutable::createFromMutable($date);
        }

        if (!is_string($date)) {
            throw InvalidDateException::forFormat($date, $format);
        }

        $format = static::resolveFormat($date, $format);

        $dateTime = \DateTimeImmutable::createFromFormat($format, $date);

        if ($dateTime === false) {
            throw InvalidDateException::forFormat($date, $format);
        }

        if (!Str::contains($format, ' ')) {
            $dateTime = $dateTime->setTime(0, 0, 0);
        }

        return $dateTime;
    }

    /**
     * @param string      $date
     * @param null|string $format
     *
     * @return string
     */
    protected static function resolveFormat(
        $date,
        $format
    ) {
        if ($format !== null) {
            return $format;
        }

        if (Str::contains($date, ' ')) {
            return 'Y-m-d H:i:s';
        }

        return 'Y-m-d';
    }
}
