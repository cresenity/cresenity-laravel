<?php

namespace Cresenity\Laravel\CElement\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\PlaceholderPropertyTrait;
use Cresenity\Laravel\CStringBuilder;

class Currency extends FormInput
{
    use PlaceholderPropertyTrait;

    public function __construct($id)
    {
        parent::__construct($id);

        $this->type = 'text';
        $this->placeholder = '';
        $this->value = '0';
        $this->addClass('form-control');
    }

    protected function build()
    {
        $this->setAttr('type', $this->type);
        $this->setAttr('value', $this->value);
    }

    public function js($indent = 0)
    {
        $js = new CStringBuilder();
        $js->setIndent($indent);
        $js->append(parent::jsChild());

        $js->append("$('#" . $this->id . "').focus( function() {
				$('#" . $this->id . "').val(cresenity.unformatCurrency($('#" . $this->id . "').val()))
			});")->br();
        $js->append("$('#" . $this->id . "').blur(function() {
				$('#" . $this->id . "').val(cresenity.formatCurrency($('#" . $this->id . "').val()))
			});")->br();

        return $js->text();
    }
}
