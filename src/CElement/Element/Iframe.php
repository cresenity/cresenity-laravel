<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;
use Cresenity\Laravel\CElement\Traits\Property\HeightPropertyTrait;
use Cresenity\Laravel\CElement\Traits\Property\WidthPropertyTrait;

class Iframe extends Element
{
    use WidthPropertyTrait,
        HeightPropertyTrait;

    protected $src = '';

    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'iframe';
    }


    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }

    public function build()
    {
        $this->setAttr('src', $this->src);
        if ($this->width) {
            $this->setAttr('width', $this->width);
        }
        if ($this->height) {
            $this->setAttr('height', $this->height);
        }
    }
}
