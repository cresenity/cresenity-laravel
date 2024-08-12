<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Td extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'td';
    }
}
