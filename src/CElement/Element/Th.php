<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Th extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'th';
    }
}
