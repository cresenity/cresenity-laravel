<?php

namespace Cresenity\Laravel\CString;

use Cresenity\Laravel\CString\Formatter\CurrencyFormatter;

class Formatter
{
    public static function currency(
        $thousandSeparator = ',',
        $decimalSeparator = '.',
        $decimalDigit = 0,
        $prefix = '',
        $suffix = ''
    ) {
        return new CurrencyFormatter(
            $thousandSeparator,
            $decimalSeparator,
            $decimalDigit,
            $prefix,
            $suffix
        );
    }
}
