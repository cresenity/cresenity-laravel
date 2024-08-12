<?php

namespace Cresenity\Laravel\CObservable\Listener\Pseudo;

use Cresenity\Laravel\CObservable\PseudoListener;

class CompleteListener extends PseudoListener
{
    public function __construct($owner)
    {
        parent::__construct($owner);
        $this->event = 'ajaxComplete';
    }
}
