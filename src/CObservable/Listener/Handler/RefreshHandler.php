<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;

class RefreshHandler extends Handler
{
    public function __construct($listener)
    {
        parent::__construct($listener);
    }

    public function js()
    {
        $js = 'window.location.reload();';

        return $js;
    }
}
