<?php

namespace Cresenity\Laravel\CObservable;

use Cresenity\Laravel\CElement;

class HandlerElement extends CElement
{
    public static function factory($id = '', $tag = 'div')
    {
        return new HandlerElement($id, $tag);
    }
}
