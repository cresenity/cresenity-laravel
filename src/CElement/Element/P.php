<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class P extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'p';
    }
}
