<?php

namespace Cresenity\Laravel\CElement\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\AutoCompletePropertyTrait;
use Cresenity\Laravel\CElement\Traits\Property\PlaceholderPropertyTrait;

class Password extends FormInput
{
    use PlaceholderPropertyTrait,
        AutoCompletePropertyTrait;

    private $showPassword = false;

    private $toggleVisibility = false;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->type = 'password';
        $this->autoComplete = false;
        $this->placeholder = '';
        $this->addClass('form-control');
    }

    public function build()
    {
        $this->setAttr('type', $this->type);
        $this->setAttr('value', $this->value);
        $this->setAttr('placeholder', $this->placeholder);
        $this->setAttr('autocomplete', $this->autoComplete ? 'on' : 'off');
        $this->addClass('cres:element:control:Password');
        $this->setAttr('cres-element', 'control:Password');
        $this->setAttr('cres-config', c::json($this->buildControlConfig()));
        if ($this->readonly) {
            $this->setAttr('readonly', 'readonly');
        }
        if ($this->showPassword) {
            $span = $this->after()->addSpan();
            $span->addClass('input-group-btn show-password text-muted fa fa-eye-slash');
        }
    }

    public function setShowPassword($bool = true)
    {
        $this->showPassword = $bool;

        return $this;
    }

    public function setToggleVisibility($bool = true)
    {
        $this->toggleVisibility = $bool;

        return $this;
    }

    protected function buildControlConfig()
    {
        $config = [
            'toggleVisibility' => (bool) $this->toggleVisibility,

        ];

        return $config;
    }
}
