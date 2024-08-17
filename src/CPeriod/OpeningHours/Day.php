<?php
namespace Cresenity\Laravel\CPeriod\OpeningHours;

class Day
{
    const MONDAY = 'monday';

    const TUESDAY = 'tuesday';

    const WEDNESDAY = 'wednesday';

    const THURSDAY = 'thursday';

    const FRIDAY = 'friday';

    const SATURDAY = 'saturday';

    const SUNDAY = 'sunday';

    public static function days()
    {
        return [
            static::MONDAY,
            static::TUESDAY,
            static::WEDNESDAY,
            static::THURSDAY,
            static::FRIDAY,
            static::SATURDAY,
            static::SUNDAY,
        ];
    }

    public static function mapDays($callback)
    {
        return \carr::map(\carr::mirror(static::days()), $callback);
    }

    public static function isValid($day)
    {
        return in_array($day, static::days());
    }

    public static function onDateTime(\DateTimeInterface $dateTime)
    {
        return static::days()[$dateTime->format('N') - 1];
    }

    public static function toISO($day)
    {
        return array_search($day, static::days()) + 1;
    }
}
