<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait WidthPropertyTrait
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
