<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * @author Hery Kurniawan
 * @license Ittron Global Teknologi <ittron.co.id>
 *
 * @since Apr 20, 2019, 3:07:56 PM
 */
class CObservable_Listener_Handler_PrependHandler extends CObservable_Listener_Handler {
    use CTrait_Compat_Handler_Driver_Prepend,
        CObservable_Listener_Handler_Trait_TargetHandlerTrait,
        CObservable_Listener_Handler_Trait_AjaxHandlerTrait;

    protected $content;

    protected $param;

    protected $param_inputs;

    protected $check_duplicate_selector;

    public function __construct($listener) {
        parent::__construct($listener);
        $this->name = 'Prepend';
        $this->method = 'get';
        $this->target = '';
        $this->content = CHandlerElement::factory();
        $this->param_inputs = [];
    }

    public function addParamInput($inputs) {
        if (!is_array($inputs)) {
            $inputs = [$inputs];
        }
        foreach ($inputs as $inp) {
            $this->param_inputs[] = $inp;
        }
        return $this;
    }

    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    public function content() {
        return $this->content;
    }

    public function setCheckDuplicateSelector($selector) {
        $this->check_duplicate_selector = $selector;
        return $this;
    }

    public function js() {
        $js = '';
        $data_addition = '';

        foreach ($this->param_inputs as $inp) {
            if (strlen($data_addition) > 0) {
                $data_addition .= ',';
            }
            $data_addition .= "'" . $inp . "':$('#" . $inp . "').val()";
        }
        $data_addition = '{' . $data_addition . '}';
        $js .= '
                    var is_duplicate = 0;
                    var check_duplicate = ' . (strlen($this->check_duplicate_selector) > 0 ? '1' : '0') . ";
                    if(check_duplicate==1){
                        if (jQuery('#" . $this->target . "').find('" . $this->check_duplicate_selector . "').length > 0) {
                            is_duplicate = 1;
                        }
                    }
                    if (is_duplicate==0) {
			$.cresenity.prepend('" . $this->target . "','" . $this->generatedUrl() . "','" . $this->method . "'," . $data_addition . ');
                    }
		';

        return $js;
    }
}
