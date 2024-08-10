<?php

namespace Cresenity\Laravel\CElement;

class PseudoElement extends Element
{
    public static function factory($id = '', $tag = 'div')
    {
        return new PseudoElement($id, $tag);
    }

    public function html($indent = 0)
    {
        return parent::htmlChild();
    }

    public function js($indent = 0)
    {
        return parent::jsChild();
    }
}
