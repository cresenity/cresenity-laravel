<?php

namespace Cresenity\Laravel\CElement\Element\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\PlaceholderPropertyTrait;
use Cresenity\Laravel\CStringBuilder;

class Text extends FormInput
{
    use PlaceholderPropertyTrait;

    protected $input_style;

    protected $button_position;

    /**
     * @var \Cresenity\Laravel\CElement\Component\Action|null
     */
    protected $action;

    public function __construct($id)
    {
        parent::__construct($id);

        $this->type = 'text';

        $this->placeholder = '';

        $this->input_style = 'default';
        $this->button_position = null;
        $this->action = null;

        $this->addClass('form-control');
    }

    /**
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\FormInput\Text
     */
    public static function factory($id = null)
    {
        return new Text($id);
    }

    public function html($indent = 0)
    {
        $html = new CStringBuilder();
        $html->setIndent($indent);
        $disabled = '';
        if ($this->disabled) {
            $disabled = ' disabled="disabled"';
        }

        if ($this->readonly) {
            $disabled = ' readonly="readonly"';
        }

        $classes = $this->classes;
        $classes = implode(' ', $classes);
        if (strlen($classes) > 0) {
            $classes = ' ' . $classes;
        }

        $custom_css = $this->custom_css;
        $custom_css = $this->renderStyle($custom_css);
        if (strlen($custom_css) > 0) {
            $custom_css = ' style="' . $custom_css . '"';
        }
        $addition_attribute = '';
        foreach ($this->attr as $k => $v) {
            $addition_attribute .= ' ' . $k . '="' . $v . '"';
        }
        $html->appendln('<input type="text" placeholder="' . \c::e($this->placeholder) . '" name="' . $this->name . '" id="' . $this->id . '" class="form-control input-unstyled' . $classes . $this->validation->validationClass() . '" value="' . \c::e($this->value) . '"' . $disabled . $custom_css . $addition_attribute . '/>')->br();

        return $html->text();
    }

    public function js($indent = 0)
    {
        $js = new CStringBuilder();
        $js->setIndent($indent);

        if ($this->action != null) {
            $js->appendln($this->action->js());
        }

        $js->append(parent::js());

        return $js->text();
    }
}
