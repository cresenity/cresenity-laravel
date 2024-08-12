<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Pre extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'pre';
        $this->haveIndent = false;
    }

    /**
     * @param string $id
     *
     * @return Pre
     */
    public static function factory($id = null)
    {
        return new Pre($id);
    }
}
