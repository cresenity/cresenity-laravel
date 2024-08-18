<?php

namespace Cresenity\Laravel\CString;

use carr;
use Cresenity\Laravel\CF;
use Cresenity\Laravel\CString\NumberToWords\EnglishNumberToWords;
use Cresenity\Laravel\CString\NumberToWords\IndonesianNumberToWords;

/**
 * @see CString
 */
class NumberToWords
{
    public static function toWords($number, $locale = null)
    {
        if ($locale == null) {
            $locale = CF::getLocale();
        }
        $defaultClass = EnglishNumberToWords::class;
        $localeMap = [
            'id_ID' => IndonesianNumberToWords::class,
            'en_US' => EnglishNumberToWords::class,

        ];
        $class = carr::get($localeMap, $locale, $defaultClass);

        return $class::toWords($number);
    }
}
