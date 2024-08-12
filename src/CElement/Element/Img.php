<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Img extends Element
{
    protected $progressiveImage = null;

    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->isOneTag = true;
        $this->tag = 'img';
    }

    /**
     * @param string $id
     *
     * @return Img
     */
    public static function factory($id = '')
    {
        return new self($id);
    }

    /**
     * Set Attribute src.
     *
     * @param string $src
     */
    public function setSrc($src)
    {
        $this->setAttr('src', $src);

        return $this;
    }

    /**
     * Set Attribute alt.
     *
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->setAttr('alt', $alt);

        return $this;
    }
}
