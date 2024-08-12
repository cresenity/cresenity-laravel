<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\SelectorHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class ReloadDataTableHandler extends Handler
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
        $selector = $this->getSelector();

        $js = "$('" . $selector . "').DataTable().ajax.reload(null, false);";

        return $js;
    }
}
