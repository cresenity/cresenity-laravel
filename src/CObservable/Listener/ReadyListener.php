<?php

namespace Cresenity\Laravel\CObservable\Listener;

use Cresenity\Laravel\CObservable\Listener;

class ReadyListener extends Listener
{
    public function __construct($owner)
    {
        parent::__construct($owner);
        $this->event = 'ready';
    }
}
