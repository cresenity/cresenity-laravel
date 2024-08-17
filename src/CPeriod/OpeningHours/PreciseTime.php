<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours;

class PreciseTime extends Time
{
    /**
     * @var \DateTimeInterface
     */
    protected $dateTime;

    protected function __construct(\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param string $string
     *
     * @return \Cresenity\Laravel\CPeriod\OpeningHours\Time
     */
    public static function fromString($string)
    {
        return self::fromDateTime(new \DateTimeImmutable($string));
    }

    /**
     * @return int
     */
    public function hours()
    {
        return (int) $this->dateTime->format('G');
    }

    public function minutes(): int
    {
        return (int) $this->dateTime->format('i');
    }

    public static function fromDateTime(\DateTimeInterface $dateTime)
    {
        return new self($dateTime);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool
     */
    public function isSame(Time $time)
    {
        return $this->format('H:i:s.u') === $time->format('H:i:s.u');
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool
     */
    public function isAfter(Time $time)
    {
        return $this->format('H:i:s.u') > $time->format('H:i:s.u');
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool
     */
    public function isBefore(Time $time)
    {
        return $this->format('H:i:s.u') < $time->format('H:i:s.u');
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return bool
     */
    public function isSameOrAfter(Time $time)
    {
        return $this->format('H:i:s.u') >= $time->format('H:i:s.u');
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\OpeningHours\Time $time
     *
     * @return \DateInterval
     */
    public function diff(Time $time)
    {
        return $this->toDateTime()->diff($time->toDateTime());
    }

    public function toDateTime(\DateTimeInterface $date = null): \DateTimeInterface
    {
        return $date
            ? $this->copyDateTime($date)->modify($this->format('H:i:s.u'))
            : $this->copyDateTime($this->dateTime);
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
            ? $this->copyDateTime($this->dateTime)->setTimezone(
                $timezone instanceof \DateTimeZone
                ? $timezone
                : new \DateTimeZone($timezone)
            )
            : $this->dateTime;

        return $date->format($format);
    }
}
