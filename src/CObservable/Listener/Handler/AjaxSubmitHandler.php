<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class AjaxSubmitHandler extends Handler
{
    use AjaxHandlerTrait,
        TargetHandlerTrait;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->name = 'AjaxSubmit';
    }

    public function js()
    {
        $optionsJson = '{';
        $optionsJson .= "selector:'#" . $this->owner . "',";
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
            cresenity.ajaxSubmit(' . $optionsJson . ');;
         ';
        return $js;
    }
}
