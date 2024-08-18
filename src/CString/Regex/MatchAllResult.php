<?php

namespace Cresenity\Laravel\CString\Regex;

use carr;
use Cresenity\Laravel\CString\Regex\Exception\RegexFailedException;

class MatchAllResult extends RegexResult
{
    protected string $pattern;

    protected string $subject;

    protected bool $result;

    protected array $matches;

    public function __construct(
        string $pattern,
        string $subject,
        bool $result,
        array $matches
    ) {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->result = $result;
        $this->matches = $matches;
    }

    /**
     * @param string $pattern
     * @param string $subject
     *
     * @return static
     */
    public static function for(string $pattern, string $subject)
    {
        $matches = [];

        try {
            $result = preg_match_all($pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL);
        } catch (\Exception $exception) {
            throw RegexFailedException::match($pattern, $subject, $exception->getMessage());
        }

        if ($result === false) {
            throw RegexFailedException::match($pattern, $subject, static::lastPregError());
        }

        return new static($pattern, $subject, $result, $matches);
    }

    public function hasMatch(): bool
    {
        return $this->result;
    }

    /**
     * @return \CString_Regex_MatchResult[]
     */
    public function results(): array
    {
        return carr::map(carr::transpose($this->matches), function ($match): MatchResult {
            return new MatchResult($this->pattern, $this->subject, true, $match);
        });
    }
}
