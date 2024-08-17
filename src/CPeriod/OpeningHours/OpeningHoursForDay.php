<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours;

use Cresenity\Laravel\CPeriod\Exception\NonMutableOffsetsException;
use Cresenity\Laravel\CPeriod\OpeningHours\Exception\OverlappingTimeRangesException;
use Cresenity\Laravel\CPeriod\OpeningHours\Traits\RangeFinderTrait;
use Cresenity\Laravel\CPeriod\Traits\DataTrait;

class OpeningHoursForDay implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use DataTrait;
    use RangeFinderTrait;

    /**
     * @var \Cresenity\Laravel\CPeriod\OpeningHours\TimeRange[]
     */
    protected $openingHours = [];

    public static function fromStrings(array $strings)
    {
        if (isset($strings['hours'])) {
            return static::fromStrings($strings['hours'])->setData($strings['data'] ?? null);
        }

        $openingHoursForDay = new static();

        if (isset($strings['data'])) {
            $openingHoursForDay->setData($strings['data'] ?? null);
            unset($strings['data']);
        }

        uasort($strings, static function ($a, $b) {
            return strcmp(static::getHoursFromRange($a), static::getHoursFromRange($b));
        });

        $timeRanges = \carr::map($strings, static function ($string) {
            return TimeRange::fromDefinition($string);
        });

        $openingHoursForDay->guardAgainstTimeRangeOverlaps($timeRanges);

        $openingHoursForDay->openingHours = $timeRanges;

        return $openingHoursForDay;
    }

    public function isOpenAt(Time $time)
    {
        foreach ($this->openingHours as $timeRange) {
            if ($timeRange->containsTime($time)) {
                return true;
            }
        }

        return false;
    }

    public function isOpenAtNight(Time $time)
    {
        foreach ($this->openingHours as $timeRange) {
            if ($timeRange->containsNightTime($time)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param callable[] $filters
     * @param bool       $reverse
     *
     * @return CPeriod_OpeningHours_Time|bool
     */
    public function openingHoursFilter(array $filters, $reverse = false)
    {
        foreach (($reverse ? array_reverse($this->openingHours) : $this->openingHours) as $timeRange) {
            foreach ($filters as $filter) {
                if ($result = $filter($timeRange)) {
                    return $result;
                }
            }
        }

        return false;
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public function nextOpen(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findOpenInFreeTime($time, $timeRange);
            },
        ]);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\TimeRange
     */
    public function nextOpenRange(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findRangeInFreeTime($time, $timeRange);
            },
        ]);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public function nextClose(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findCloseInWorkingHours($time, $timeRange);
            },
            function ($timeRange) use ($time) {
                return $this->findCloseInFreeTime($time, $timeRange);
            },
        ]);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\TimeRange
     */
    public function nextCloseRange(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findCloseRangeInWorkingHours($time, $timeRange);
            },
            function ($timeRange) use ($time) {
                return $this->findRangeInFreeTime($time, $timeRange);
            },
        ]);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public function previousOpen(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findPreviousOpenInFreeTime($time, $timeRange);
            },
            function ($timeRange) use ($time) {
                return $this->findOpenInWorkingHours($time, $timeRange);
            },
        ], true);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|TimeRange
     */
    public function previousOpenRange(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findRangeInFreeTime($time, $timeRange);
            },
        ], true);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public function previousClose(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findPreviousCloseInWorkingHours($time, $timeRange);
            },
        ], true);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool|\Cresenity\Laravel\CPeriod\OpeningHours\TimeRange
     */
    public function previousCloseRange(Time $time)
    {
        return $this->openingHoursFilter([
            function ($timeRange) use ($time) {
                return $this->findPreviousRangeInFreeTime($time, $timeRange);
            },
        ], true);
    }

    protected static function getHoursFromRange($range)
    {
        return strval((
            is_array($range)
            ? (isset($range['hours']) ? $range['hours'] : (isset(array_values($range)[0]) ? array_values($range)[0] : null))
            : null
        ) ?: $range);
    }

    /**
     * @param [type] $offset
     *
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->openingHours[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->openingHours[$offset];
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw NonMutableOffsetsException::forClass(static::class);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->openingHours[$offset]);
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->openingHours);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->openingHours);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return \Cresenity\Laravel\CPeriod\OpeningHours\TimeRange[]|Generator
     */
    public function forTime(CPeriod_OpeningHours_Time $time)
    {
        foreach ($this as $range) {
            /* @var TimeRange $range */

            if ($range->containsTime($time)) {
                yield $range;
            }
        }
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return \Cresenity\Laravel\CPeriod\OpeningHours\TimeRange[]|Generator
     */
    public function forNightTime(CPeriod_OpeningHours_Time $time)
    {
        foreach ($this as $range) {
            /* @var TimeRange $range */

            if ($range->containsNightTime($time)) {
                yield $range;
            }
        }
    }

    public function isEmpty(): bool
    {
        return empty($this->openingHours);
    }

    public function map(callable $callback)
    {
        return \carr::map($this->openingHours, $callback);
    }

    protected function guardAgainstTimeRangeOverlaps(array $openingHours)
    {
        foreach (Helper::createUniquePairs($openingHours) as $timeRanges) {
            if ($timeRanges[0]->overlaps($timeRanges[1])) {
                throw OverlappingTimeRangesException::forRanges($timeRanges[0], $timeRanges[1]);
            }
        }
    }

    public function __toString()
    {
        $values = [];

        foreach ($this->openingHours as $openingHour) {
            $values[] = (string) $openingHour;
        }

        return implode(',', $values);
    }
}
