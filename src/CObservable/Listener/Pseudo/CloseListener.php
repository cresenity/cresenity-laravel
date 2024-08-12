<?php

namespace Cresenity\Laravel\CObservable\Listener\Pseudo;

use Cresenity\Laravel\CObservable\PseudoListener;

class CloseListener extends PseudoListener
{
    public function __construct($owner)
    {
        parent::__construct($owner);
        $this->event = 'close';
        $this->eventParameters = ['e'];
    }
}
