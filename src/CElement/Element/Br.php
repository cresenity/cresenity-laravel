<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Br extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->isOneTag = true;
        $this->tag = 'br';
    }
}
