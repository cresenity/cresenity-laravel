<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait HeightPropertyTrait
{
    /**
     * @var int
     */
    protected $height;

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}
