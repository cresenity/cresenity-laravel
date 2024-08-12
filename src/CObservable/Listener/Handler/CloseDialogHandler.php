<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;

class CloseDialogHandler extends Handler
{
    public function __construct($listener)
    {
        parent::__construct($listener);

        $this->name = 'Custom';
    }

    public function js()
    {
        $js = '';
        $js = 'cresenity.closeDialog();';

        return $js;
    }
}
