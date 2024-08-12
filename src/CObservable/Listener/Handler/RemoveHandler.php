<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class RemoveHandler extends Handler
{
    use TargetHandlerTrait;

    protected $parent;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->target = $this->owner;
        $this->name = 'Remove';
        $this->parent = '';
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function js()
    {
        $js = '';
        $js .= 'jQuery("#' . $this->target . '")';
        if (strlen($this->parent) > 0) {
            $js .= '.parents("' . $this->parent . '")';
        }
        $js .= '.remove();';

        return $js;
    }
}
