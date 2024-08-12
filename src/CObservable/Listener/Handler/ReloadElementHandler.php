<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\SelectorHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class ReloadElementHandler extends Handler
{
    use TargetHandlerTrait,
        SelectorHandlerTrait;

    public function __construct($listener)
    {
        parent::__construct($listener);

        $this->target = '';
        $this->selector = '';
    }

    public function js()
    {
        $jsOptions = '{';
        $jsOptions .= "selector:'" . $this->getSelector() . "',";
        $jsOptions .= '}';

        $js = '';
        $js .= '
                cresenity.reload(' . $jsOptions . ');
         ';

        return $js;
    }
}
