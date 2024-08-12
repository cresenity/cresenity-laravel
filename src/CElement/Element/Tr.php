<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Tr extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'tr';
    }
}
