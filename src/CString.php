<?php

namespace Cresenity\Laravel;

use Cresenity\Laravel\CBase\ForwarderStaticClass;
use Cresenity\Laravel\CString\Formatter;
use Cresenity\Laravel\CString\Initials;
use Cresenity\Laravel\CString\Language;
use Cresenity\Laravel\CString\NumberToWords;
use Cresenity\Laravel\CString\PatternBuilder;
use Cresenity\Laravel\CString\Regex;

class CString
{
    public static function initials($name = null)
    {
        return new Initials($name);
    }

    public static function language()
    {
        return new Language();
    }

    public static function createPatternBuilder()
    {
        return new PatternBuilder();
    }

    /**
     * @param string      $number
     * @param null|string $locale
     *
     * @throws Symfony\Component\Routing\Exception\InvalidParameterException
     *
     * @return string
     */
    public static function numberToWords($number, $locale = null)
    {
        return NumberToWords::toWords($number, $locale);
    }

    /**
     * @return CString_Formatter
     */
    public static function formatter()
    {
        return new ForwarderStaticClass(Formatter::class);
    }

    /**
     * @return CString_Regex
     */
    public static function regex()
    {
        return new ForwarderStaticClass(Regex::class);
    }
}
