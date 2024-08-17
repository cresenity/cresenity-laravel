<?php
namespace Cresenity\Laravel\CPeriod;

class Duration
{
    /**
     * @var \Cresenity\Laravel\CPeriod
     */
    private $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    /**
     * @param Duration $other
     *
     * @return bool
     */
    public function equals(Duration $other)
    {
        return $this->startAndEndDatesAreTheSameAs($other)
            || $this->includedStartAndEndDatesAreTheSameAs($other)
            || $this->numberOfDaysIsTheSameAs($other)
            || $this->compareTo($other) === 0;
    }

    /**
     * @param Duration $other
     *
     * @return bool
     */
    public function isLargerThan(Duration $other)
    {
        return $this->compareTo($other) === 1;
    }

    /**
     * @param Duration $other
     *
     * @return bool
     */
    public function isSmallerThan(Duration $other)
    {
        return $this->compareTo($other) === -1;
    }

    /**
     * @param Duration $other
     *
     * @return int
     */
    public function compareTo(Duration $other)
    {
        $now = new \DateTimeImmutable('@' . time()); // Ensure a TimeZone independent instance

        $here = $this->period->includedEnd()->diff($this->period->includedStart(), true);
        $there = $other->period->includedEnd()->diff($other->period->includedStart(), true);

        return \c::spaceshipOperator($now->add($here)->getTimestamp(), $now->add($there)->getTimestamp());
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\Duration $other
     *
     * @return bool
     */
    private function startAndEndDatesAreTheSameAs(Duration $other)
    {
        return $this->period->start() == $other->period->start()
            && $this->period->end() == $other->period->end();
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\Duration $other
     *
     * @return bool
     */
    private function includedStartAndEndDatesAreTheSameAs(Duration $other)
    {
        return $this->period->includedStart() == $other->period->includedStart()
            && $this->period->includedEnd() == $other->period->includedEnd();
    }

    /**
     * @param \Cresenity\Laravel\CPeriod\Duration $other
     *
     * @return void
     */
    private function numberOfDaysIsTheSameAs(Duration $other)
    {
        $here = $this->period->includedEnd()->diff($this->period->includedStart(), true);
        $there = $other->period->includedEnd()->diff($other->period->includedStart(), true);

        return $here->format('%a') === $there->format('%a');
    }
}
