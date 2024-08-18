<?php

namespace Cresenity\Laravel\CElement\FormInput;

class Csrf extends Hidden
{
    public function __construct($id)
    {
        parent::__construct($id);

        $this->value = \c::csrfToken();
        $this->name = '_token';
    }

    public static function factory($id = null)
    {
        return new Csrf($id);
    }
}
