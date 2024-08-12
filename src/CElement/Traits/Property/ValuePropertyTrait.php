<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait ValuePropertyTrait
{
    /**
     * @var int
     */
    protected $value;

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
