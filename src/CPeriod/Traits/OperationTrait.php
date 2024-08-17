<?php
namespace Cresenity\Laravel\CPeriod\Traits;

use Cresenity\Laravel\CPeriod;
use Cresenity\Laravel\CPeriod\Collection;
use Cresenity\Laravel\CPeriod\Factory;

/** @mixin CPeriod */
trait OperationTrait
{
    /**
     * @param CPeriod $period
     *
     * @return null|static
     */
    public function gap(CPeriod $period)
    {
        $this->ensurePrecisionMatches($period);

        if ($this->overlapsWith($period)) {
            return null;
        }

        if ($this->touchesWith($period)) {
            return null;
        }

        if ($this->includedStart() >= $period->includedEnd()) {
            return static::make(
                $period->includedEnd()->add($this->interval),
                $this->includedStart()->sub($this->interval),
                $this->precision()
            );
        }

        return static::make(
            $this->includedEnd()->add($this->interval),
            $period->includedStart()->sub($this->interval),
            $this->precision()
        );
    }

    /**
     * @param \Cresenity\Laravel\CPeriod ...$others
     *
     * @return null|static
     */
    public function overlap(CPeriod ...$others)
    {
        if (count($others) === 0) {
            return null;
        } elseif (count($others) > 1) {
            return $this->overlapAll(...$others);
        } else {
            $other = $others[0];
        }

        $this->ensurePrecisionMatches($other);

        $includedStart = $this->includedStart() > $other->includedStart()
            ? $this->includedStart()
            : $other->includedStart();

        $includedEnd = $this->includedEnd() < $other->includedEnd()
            ? $this->includedEnd()
            : $other->includedEnd();

        if ($includedStart > $includedEnd) {
            return null;
        }

        return Factory::makeWithBoundaries(
            static::class,
            $includedStart,
            $includedEnd,
            $this->precision(),
            $this->boundaries()
        );
    }

    /**
     * @param \Cresenity\Laravel\CPeriod ...$periods
     *
     * @return null|static
     */
    protected function overlapAll(CPeriod ...$periods)
    {
        $overlap = clone $this;

        if (!count($periods)) {
            return $overlap;
        }

        foreach ($periods as $period) {
            $overlap = $overlap->overlap($period);

            if ($overlap === null) {
                return null;
            }
        }

        return $overlap;
    }

    /**
     * @param \Cresenity\Laravel\CPeriod ...$others
     *
     * @return \Cresenity\Laravel\CPeriod\Collection|static[]
     */
    public function overlapAny(CPeriod ...$others)
    {
        $overlapCollection = new Collection();

        foreach ($others as $period) {
            $overlap = $this->overlap($period);

            if ($overlap === null) {
                continue;
            }

            $overlapCollection[] = $overlap;
        }

        return $overlapCollection;
    }

    /**
     * @param \Cresenity\Laravel\CPeriod|iterable $other
     *
     * @return \Cresenity\Laravel\CPeriod\CPeriod_Collection|static[]
     */
    public function subtract(CPeriod ...$others)
    {
        if (count($others) === 0) {
            return Collection::make($this);
        } elseif (count($others) > 1) {
            return $this->subtractAll(...$others);
        } else {
            $other = $others[0];
        }

        $this->ensurePrecisionMatches($other);

        $collection = new Collection();

        if (!$this->overlapsWith($other)) {
            $collection[] = $this;

            return $collection;
        }

        if ($this->includedStart() < $other->includedStart()) {
            $collection[] = Factory::makeWithBoundaries(
                $this->includedStart(),
                $other->includedStart()->sub($this->interval),
                $this->precision(),
                $this->boundaries()
            );
        }

        if ($this->includedEnd() > $other->includedEnd()) {
            $collection[] = Factory::makeWithBoundaries(
                $other->includedEnd()->add($this->interval),
                $this->includedEnd(),
                $this->precision(),
                $this->boundaries()
            );
        }

        return $collection;
    }

    /**
     * @param \Cresenity\Laravel\CPeriod ...$others
     *
     * @return \Cresenity\Laravel\CPeriod\Collection
     */
    protected function subtractAll(CPeriod ...$others)
    {
        $subtractions = [];

        foreach ($others as $other) {
            $subtractions[] = $this->subtract($other);
        }

        return (new Collection($this))->overlapAll(...$subtractions);
    }

    /**
     * @param \Cresenity\Laravel\CPeriod $other
     *
     * @return \Cresenity\Laravel\CPeriod\Collection|static[]
     */
    public function diffSymmetric(CPeriod $other)
    {
        $this->ensurePrecisionMatches($other);

        $periodCollection = new Collection();

        if (!$this->overlapsWith($other)) {
            $periodCollection[] = clone $this;
            $periodCollection[] = clone $other;

            return $periodCollection;
        }

        $boundaries = (new Collection($this, $other))->boundaries();

        $overlap = $this->overlap($other);

        return $boundaries->subtract($overlap);
    }

    /**
     * @return static
     */
    public function renew()
    {
        $length = $this->includedStart->diff($this->includedEnd);

        $start = $this->includedEnd->add($this->interval);

        $end = $start->add($length);

        return static::make($start, $end, $this->precision, $this->boundaries);
    }
}
