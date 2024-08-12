<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\ParamHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class AjaxHandler extends Handler
{
    use AjaxHandlerTrait,
        TargetHandlerTrait,
        ParamHandlerTrait;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->name = 'Ajax';
        $this->method = 'post';
        $this->url = '';
    }

    public function js()
    {
        $dataAddition = $this->populateParamJson();
        $generatedUrl = $this->generatedUrl();
        $optionsJson = '{';
        $optionsJson .= "url:'" . $generatedUrl . "',";
        $optionsJson .= "method:'" . $this->method . "',";
        $optionsJson .= 'dataAddition:' . $dataAddition . ',';
        if ($this->haveCompleteListener()) {
            $optionsJson .= 'onComplete: ' . $this->getCompleteListener()->js() . ',';
        }
        if ($this->haveSuccessListener()) {
            $optionsJson .= 'onSuccess: ' . $this->getSuccessListener()->js() . ',';
        }
        $optionsJson .= 'handleJsonResponse: true,';
        $optionsJson .= '}';
        $js = '';
        $js .= '
            cresenity.ajax(' . $optionsJson . ');
         ';

        return $js;
    }
}
