<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;

class CustomHandler extends Handler
{
    protected $js;

    public function __construct($listener)
    {
        parent::__construct($listener);

        $this->name = 'Custom';
    }

    public function setJs($js)
    {
        $this->js = $js;

        return $this;
    }

    public function js()
    {
        $js = '';
        $js .= $this->js;

        return $js;
    }
}
