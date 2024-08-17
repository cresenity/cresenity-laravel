<?php
namespace Cresenity\Laravel\CPeriod\Traits;

use Cresenity\Laravel\CPeriod\Exception\CannotCeilLowerPrecisionException;
use Cresenity\Laravel\CPeriod\Precision;

/** @mixin CPeriod */
trait GetterTrait
{
    /**
     * @var string
     */
    protected $asString;

    /**
     * @return bool
     */
    public function isStartIncluded()
    {
        return $this->boundaries->startIncluded();
    }

    /**
     * @return bool
     */
    public function isStartExcluded()
    {
        return $this->boundaries->startExcluded();
    }

    /**
     * @return bool
     */
    public function isEndIncluded()
    {
        return $this->boundaries->endIncluded();
    }

    /**
     * @return bool
     */
    public function isEndExcluded()
    {
        return $this->boundaries->endExcluded();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function includedStart()
    {
        return $this->includedStart;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function includedEnd()
    {
        return $this->includedEnd;
    }

    /**
     * @param null|\Cresenity\Laravel\CPeriod\Precision $precision
     *
     * @throws CannotCeilLowerPrecisionException
     * @return \DateTimeImmutable
     */
    public function ceilingEnd(Precision $precision = null)
    {
        $precision = $precision ?: $this->precision;

        if ($precision->higherThan($this->precision)) {
            throw CannotCeilLowerPrecisionException::precisionIsLower($this->precision, $precision);
        }

        return $this->precision->ceilDate($this->includedEnd, $precision);
    }

    /**
     * @return int
     */
    public function length()
    {
        // Length of month and year are not fixed, so we can't predict the length without iterate
        // TODO: maybe we can use cal_days_in_month ?
        if ($this->precision->equals(Precision::MONTH(), Precision::YEAR())) {
            return iterator_count($this);
        }

        if ($this->precision->equals(Precision::HOUR(), Precision::MINUTE(), Precision::SECOND())) {
            $length = abs($this->includedEnd()->getTimestamp() - $this->includedStart()->getTimestamp());

            if ($this->precision->equals(Precision::SECOND())) {
                return $length + 1;
            }

            $length = floor($length / 60);

            if ($this->precision->equals(Precision::MINUTE())) {
                return $length + 1;
            }

            return floor($length / 60) + 1;
        }

        return $this->includedStart()->diff($this->includedEnd())->days + 1;
    }

    /**
     * @return \Cresenity\Laravel\CPeriod\Duration
     */
    public function duration()
    {
        return $this->duration;
    }

    /**
     * @return \Cresenity\Laravel\CPeriod\Precision
     */
    public function precision()
    {
        return $this->precision;
    }

    /**
     * @return \Cresenity\Laravel\CPeriod\Boundaries
     */
    public function boundaries()
    {
        return $this->boundaries;
    }

    /**
     * @return string
     */
    public function asString()
    {
        if (!isset($this->asString)) {
            $this->asString = $this->resolveString();
        }

        return $this->asString;
    }

    /**
     * @return string
     */
    private function resolveString()
    {
        $string = '';

        if ($this->isStartIncluded()) {
            $string .= '[';
        } else {
            $string .= '(';
        }

        $string .= $this->start()->format($this->precision->dateFormat());

        $string .= ',';

        $string .= $this->end()->format($this->precision->dateFormat());

        if ($this->isEndIncluded()) {
            $string .= ']';
        } else {
            $string .= ')';
        }

        return $string;
    }
}
