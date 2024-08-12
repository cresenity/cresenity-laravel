<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler\Traits;

use Cresenity\Laravel\CObservable\Listener\Pseudo\CloseListener;

trait CloseHandlerTrait
{
    public function onCloseListener()
    {
        if (!isset($this->handlerListeners['close'])) {
            $this->handlerListeners['close'] = new CloseListener($this);
        }
        return $this->handlerListeners['close'];
    }

    public function haveCloseListener()
    {
        return $this->haveListener('close');
    }

    public function getCloseListener()
    {
        return $this->getListener('close');
    }
}
