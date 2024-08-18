<?php

namespace Cresenity\Laravel\CElement\FormInput\QueryBuilder\Filter;

use Cresenity\Laravel\CElement\FormInput\QueryBuilder\Constant;

trait InputTrait
{
    protected $input;

    public function setInputText()
    {
        $this->input = Constant::FILTER_INPUT_TEXT;
        $this->values = null;

        return $this;
    }

    public function setInputTextarea()
    {
        $this->input = Constant::FILTER_INPUT_TEXTAREA;
        $this->values = null;

        return $this;
    }

    public function setInputSelect($list)
    {
        $this->input = Constant::FILTER_INPUT_SELECT;
        $this->values = $list;

        return $this;
    }

    public function setInputRadio($list)
    {
        $this->input = Constant::FILTER_INPUT_RADIO;
        $this->values = $list;

        return $this;
    }

    public function setInputCheckbox($list)
    {
        $this->input = Constant::FILTER_INPUT_CHECKBOX;
        $this->values = $list;

        return $this;
    }
}
