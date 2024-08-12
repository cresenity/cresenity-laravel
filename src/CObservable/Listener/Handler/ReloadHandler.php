<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\HandlerElement;
use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\BlockerHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\ParamHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\SelectorHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class ReloadHandler extends Handler
{
    use TargetHandlerTrait,
        SelectorHandlerTrait,
        AjaxHandlerTrait,
        BlockerHandlerTrait,
        ParamHandlerTrait;

    protected $content;

    protected $param;

    protected $urlParam;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->method = 'get';
        $this->target = '';
        $this->content = HandlerElement::factory();
        $this->paramInputs = [];
        $this->paramInputsByName = [];
        $this->paramRequest = [];
        $this->name = 'Reload';
        $this->url = '';
        $this->urlParam = [];
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
            cresenity.reload(' . $jsOptions . ');
        ';

        return $js;
    }
}
