<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception\InvalidTimeRangeArrayException;
use Cresenity\Laravel\CPeriod\OpeningHours\Exception\InvalidTimeRangeListException;
use Cresenity\Laravel\CPeriod\OpeningHours\Exception\InvalidTimeRangeStringException;
use Cresenity\Laravel\CPeriod\Traits\DataTrait;

class TimeRange
{
    use DataTrait;

    /**
     * @var \Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    protected $start;

    /**
     * @var \Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    protected $end;

    protected function __construct(Time $start, Time $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function fromString(string $string): self
    {
        $times = explode('-', $string);

        if (count($times) !== 2) {
            throw InvalidTimeRangeStringException::forString($string);
        }

        return new self(Time::fromString($times[0]), Time::fromString($times[1]));
    }

    public static function fromArray(array $array): self
    {
        $values = [];
        $keys = ['hours', 'data'];

        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $values[$key] = $array[$key];
                unset($array[$key]);

                continue;
            }
        }

        foreach ($keys as $key) {
            if (!isset($values[$key])) {
                $values[$key] = array_shift($array);
            }
        }

        if (!$values['hours']) {
            throw InvalidTimeRangeArrayException::create();
        }

        return static::fromString($values['hours'])->setData($values['data']);
    }

    public static function fromDefinition($value): self
    {
        return is_array($value) ? static::fromArray($value) : static::fromString($value);
    }

    public static function fromList(array $ranges): self
    {
        if (count($ranges) === 0) {
            throw InvalidTimeRangeListException::create();
        }

        foreach ($ranges as $range) {
            if (!($range instanceof self)) {
                throw InvalidTimeRangeListException::create();
            }
        }

        $start = $ranges[0]->start();
        $end = $ranges[0]->end();

        foreach (array_slice($ranges, 1) as $range) {
            $rangeStart = $range->start();
            if ($rangeStart->isBefore($start)) {
                $start = $rangeStart;
            }
            $rangeEnd = $range->end();
            if ($rangeEnd->isAfter($end)) {
                $end = $rangeEnd;
            }
        }

        return new self($start, $end);
    }

    public static function fromMidnight(Time $end)
    {
        return new self(Time::fromString('00:00'), $end);
    }

    /**
     * @return \Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return \Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @return bool
     */
    public function isReversed()
    {
        return $this->start()->isAfter($this->end());
    }

    /**
     * @return bool
     */
    public function overflowsNextDay()
    {
        return $this->isReversed();
    }

    /**
     * @return bool
     */
    public function spillsOverToNextDay()
    {
        return $this->isReversed();
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool
     */
    public function containsTime(Time $time)
    {
        return $time->isSameOrAfter($this->start) && ($this->overflowsNextDay() || $time->isBefore($this->end));
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool
     */
    public function containsNightTime(Time $time)
    {
        return $this->overflowsNextDay() && self::fromMidnight($this->end())->containsTime($time);
    }

    /**
     * @param self $timeRange
     *
     * @return bool
     */
    public function overlaps($timeRange)
    {
        return $this->containsTime($timeRange->start) || $this->containsTime($timeRange->end);
    }

    /**
     * @param string $timeFormat
     * @param string $rangeFormat
     * @param mixed  $timezone
     *
     * @return string
     */
    public function format($timeFormat = 'H:i', $rangeFormat = '%s-%s', $timezone = null)
    {
        return sprintf($rangeFormat, $this->start->format($timeFormat, $timezone), $this->end->format($timeFormat, $timezone));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }
}
