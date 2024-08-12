<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait AlignPropertyTrait
{
    /**
     * @var string
     */
    protected $align;

    /**
     * @param string $align
     *
     * @return $this
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }
}
