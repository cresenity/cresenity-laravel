<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class EmptyHandler extends Handler
{
    use TargetHandlerTrait;

    public function __construct($listener)
    {
        parent::__construct($listener);

        $this->name = 'Empty';
    }

    public function js()
    {
        $js = '';

        $js .= "
			jQuery('#" . $this->target . "').empty();

		";

        return $js;
    }
}
