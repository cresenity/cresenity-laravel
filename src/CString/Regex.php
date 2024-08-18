<?php

namespace Cresenity\Laravel\CString;

use Cresenity\Laravel\CString\Regex\MatchAllResult;
use Cresenity\Laravel\CString\Regex\MatchResult;
use Cresenity\Laravel\CString\Regex\ReplaceResult;

/**
 * @see CString
 */
class Regex
{
    public static function match(string $pattern, string $subject): MatchResult
    {
        return MatchResult::for($pattern, $subject);
    }

    public static function matchAll(string $pattern, string $subject): MatchAllResult
    {
        return MatchAllResult::for($pattern, $subject);
    }

    /**
     * @param string|array          $pattern
     * @param string|array|callable $replacement
     * @param string|array          $subject
     * @param int                   $limit
     */
    public static function replace(
        $pattern,
        $replacement,
        $subject,
        $limit = -1
    ): ReplaceResult {
        return ReplaceResult::for($pattern, $replacement, $subject, $limit);
    }
}
