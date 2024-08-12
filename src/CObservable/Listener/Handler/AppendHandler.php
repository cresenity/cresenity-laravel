<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\HandlerElement;
use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\BlockerHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\ParamHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\SelectorHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class AppendHandler extends Handler
{
    use TargetHandlerTrait,
        SelectorHandlerTrait,
        AjaxHandlerTrait,
        BlockerHandlerTrait,
        ParamHandlerTrait;

    protected $content;

    protected $param;

    protected $checkDuplicateSelector;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->name = 'Append';
        $this->method = 'get';
        $this->target = '';
        $this->content = HandlerElement::factory();
        $this->paramInputs = [];
        $this->paramInputsByName = [];
        $this->paramRequest = [];
        $this->url = '';
        // $this->urlParam = [];
    }

    public function content()
    {
        return $this->content;
    }

    public function toAttributeArray()
    {
        return [
            'selector' => $this->getSelector(),
            'url' => $this->generatedUrl(),
            'method' => $this->method,
            'blockType' => $this->blockerType,
        ];
    }

    /**
     * Set duplicate css selector checker.
     *
     * @param string $selector
     *
     * @return $this
     */
    public function setCheckDuplicateSelector($selector)
    {
        $this->checkDuplicateSelector = $selector;

        return $this;
    }

    public function js()
    {
        $js = '';
        $dataAddition = $this->populateParamJson();

        $generatedUrl = $this->generatedUrl();
        $jsOptions = '{';
        $jsOptions .= "selector:'" . $this->getSelector() . "',";
        $jsOptions .= "url:'" . $generatedUrl . "',";
        $jsOptions .= "method:'" . $this->method . "',";
        $jsOptions .= 'dataAddition:' . $dataAddition . ',';
        $jsOptions .= "blockType:'" . $this->getBlockerType() . "',";

        $jsOptions .= '}';

        $js .= '
            var isDuplicate = 0;
            var checkDuplicate = ' . (strlen($this->checkDuplicateSelector) > 0 ? '1' : '0') . ';
            if (checkDuplicate == 1) {
                if (jQuery("#' . $this->target . '").find("' . $this->checkDuplicateSelector . '").length > 0) {
                    isDuplicate = 1;
                }
            }

            if (isDuplicate == 0) {
                if (cresenity) {
                    cresenity.append(' . $jsOptions . ');
                } else {
                    $.cresenity.append("' . $this->target . '", "' . $generatedUrl . '", "' . $this->method . '", ' . $dataAddition . ');
                }
            }
         ';

        return $js;
    }
}
