<?php

namespace Cresenity\Laravel\CElement\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\DependsOnPropertyTrait;
use Cresenity\Laravel\CElement\Traits\TransformTrait;

class Hidden extends FormInput
{
    use DependsOnPropertyTrait;
    use TransformTrait;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->type = 'hidden';
    }

    public static function factory($id = null)
    {
        return new CElement_FormInput_Hidden($id);
    }

    protected function build()
    {
        $value = $this->value;
        $value = $this->applyTransform($value);
        $this->setAttr('type', $this->type);
        $this->setAttr('value', (string) $value);
        $this->setAttr('name', $this->name);
        $this->addJs($this->getDependsOnValueJavascript());
        parent::build();
    }
}
