<?php

namespace Cresenity\Laravel\CElement\Element\FormInput;

use Cresenity\Laravel\CElement\FormInput;

class CElement_FormInput_RadioList extends CElement_FormInput
{
    public function __construct($id)
    {
        parent::__construct($id);

        $this->tag = 'div';
        $this->addClass('checkbox-list');
    }

    protected function build()
    {
        parent::build();
        foreach ($this->list as $key => $value) {
            $controlName = $this->name ?: $this->id;
            $this->addRadioControl()->setName($controlName)->setValue($key)->setLabel($value);
        }
    }
}
