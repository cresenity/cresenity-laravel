<?php

namespace Cresenity\Laravel\CElement\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\PlaceholderPropertyTrait;

class Email extends FormInput
{
    use PlaceholderPropertyTrait;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->type = 'email';
        $this->placeholder = '';
        $this->addClass('form-control');
    }

    protected function build()
    {
        $this->setAttr('type', $this->type);
        $this->setAttr('value', $this->value);

        $this->setAttr('placeholder', $this->placeholder);

        if ($this->readonly) {
            $this->setAttr('readonly', 'readonly');
        }
    }
}
