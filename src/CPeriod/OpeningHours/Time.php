<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours;

use Cresenity\Laravel\CPeriod\OpeningHours\Exception\InvalidTimeStringException;
use Cresenity\Laravel\CPeriod\Traits\DataTrait;
use Cresenity\Laravel\CPeriod\Traits\DateTimeCopierTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;

class Time
{
    use DataTrait;
    use DateTimeCopierTrait;

    /**
     * @var int
     */
    protected $hours;

    /**
     * @var int
     */
    protected $minutes;

    /**
     * @param int $hours
     * @param int $minutes
     */
    protected function __construct($hours, $minutes)
    {
        $this->hours = $hours;
        $this->minutes = $minutes;
    }

    /**
     * @param string $string
     *
     * @return self
     */
    public static function fromString($string)
    {
        if (!preg_match('/^(([0-1][0-9]|2[0-3]):[0-5][0-9]|24:00)$/', $string)) {
            throw InvalidTimeStringException::forString($string);
        }

        list($hours, $minutes) = explode(':', $string);

        return new self($hours, $minutes);
    }

    /**
     * @return int
     */
    public function hours()
    {
        return $this->hours;
    }

    /**
     * @return int
     */
    public function minutes()
    {
        return $this->minutes;
    }

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return self
     */
    public static function fromDateTime(\DateTimeInterface $dateTime)
    {
        return static::fromString($dateTime->format('H:i'));
    }

    /**
     * @param self $time
     *
     * @return bool
     */
    public function isSame(Time $time)
    {
        return $this->hours === $time->hours && $this->minutes === $time->minutes;
    }

    /**
     * @param self $time
     *
     * @return bool
     */
    public function isAfter(Time $time)
    {
        if ($this->isSame($time)) {
            return false;
        }

        if ($this->hours > $time->hours) {
            return true;
        }

        return $this->hours === $time->hours && $this->minutes >= $time->minutes;
    }

    /**
     * @param self $time
     *
     * @return bool
     */
    public function isBefore(Time $time)
    {
        if ($this->isSame($time)) {
            return false;
        }

        return !$this->isAfter($time);
    }

    /**
     * @param self $time
     *
     * @return bool
     */
    public function isSameOrAfter(Time $time)
    {
        return $this->isSame($time) || $this->isAfter($time);
    }

    /**
     * @param self $time
     *
     * @return DateInterval
     */
    public function diff(Time $time)
    {
        return $this->toDateTime()->diff($time->toDateTime());
    }

    /**
     * @param null|\DateTimeInterface $date
     *
     * @return \DateTimeInterface
     */
    public function toDateTime(\DateTimeInterface $date = null)
    {
        $date = $date ? $this->copyDateTime($date) : new \DateTime('1970-01-01 00:00:00');

        return $date->setTime($this->hours, $this->minutes);
    }

    /**
     * @param string $format
     * @param mixed  $timezone
     *
     * @return string
     */
    public function format($format = 'H:i', $timezone = null)
    {
        $date = $timezone
            ? new DateTime(
                '1970-01-01 00:00:00',
                $timezone instanceof DateTimeZone
                ? $timezone
                : new DateTimeZone($timezone)
            )
            : null;

        if ($this->hours === 24 && $this->minutes === 0 && substr($format, 0, 3) === 'H:i') {
            return '24:00' . (
                strlen($format) > 3
                    ? ($date ?? new DateTimeImmutable('1970-01-01 00:00:00'))->format(substr($format, 3))
                    : ''
            );
        }

        return $this->toDateTime($date)->format($format);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }
}
