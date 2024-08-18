<?php

namespace Cresenity\Laravel\CString\Regex;

use Cresenity\Laravel\CString\Regex\Exception\RegexFailedException;

class MatchResult extends RegexResult
{
    protected string $pattern;

    protected string $subject;

    protected bool $hasMatch;

    protected array $matches;

    public function __construct(
        string $pattern,
        string $subject,
        bool $hasMatch,
        array $matches
    ) {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->hasMatch = $hasMatch;
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
            $result = preg_match($pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL);
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
        return $this->hasMatch;
    }

    public function result(): ?string
    {
        return $this->matches[0] ?? null;
    }

    public function resultOr(string $default): string
    {
        return $this->result() ?? $default;
    }

    /**
     * Match group by index or name.
     *
     * @param int|string $group
     *
     * @throws RegexFailedException
     *
     * @return string
     */
    public function group($group): string
    {
        if (!isset($this->matches[$group])) {
            throw RegexFailedException::groupDoesntExist($this->pattern, $this->subject, $group);
        }

        return $this->matches[$group];
    }

    /**
     * Return an array of the matches.
     *
     * @return array
     */
    public function groups(): array
    {
        return $this->matches;
    }

    /**
     * Match group by index or return default value if group doesn't exist.
     *
     * @param int|string $group
     * @param string     $default
     *
     * @return string
     */
    public function groupOr($group, string $default): string
    {
        try {
            return $this->group($group);
        } catch (RegexFailedException $e) {
            return $default;
        }
    }

    /**
     * Match group by index or name.
     *
     * @param int|string $group
     *
     * @throws RegexFailedException
     *
     * @return string
     */
    public function namedGroup($group): string
    {
        return $this->group($group);
    }
}
