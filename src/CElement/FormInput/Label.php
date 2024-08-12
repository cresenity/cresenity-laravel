<?php

namespace Cresenity\Laravel\CElement\Element\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\DependsOnPropertyTrait;
use Cresenity\Laravel\CElement\Traits\TransformTrait;
use Cresenity\Laravel\CRenderable;

class Label extends FormInput
{
    use DependsOnPropertyTrait;
    use TransformTrait;

    public function __construct($id)
    {
        parent::__construct($id);

        $this->tag = 'span';
        $this->type = 'label';
        $this->isOneTag = false;
    }

    public static function factory($id = null)
    {
        /** @phpstan-ignore-next-line */
        return new static($id);
    }

    protected function build()
    {
        $value = $this->value;

        $value = $this->applyTransform($value);
        if ($value instanceof CRenderable) {
            $this->value = $value->html();
            $this->addJs($value->js());
            $value = $this->value;
        }

        $this->value = (string) $value;

        $this->addJs($this->getDependsOnContentJavascript());
        $this->addClass('label');
        $this->setAttr('name', $this->name);
        parent::build();

        $this->setAttr('value', '');
        $this->add($this->value);
    }
}
