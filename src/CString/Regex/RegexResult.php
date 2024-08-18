<?php

namespace Cresenity\Laravel\CString\Regex;

abstract class RegexResult
{
    protected static function lastPregError(): string
    {
        return preg_last_error_msg();
    }
}
