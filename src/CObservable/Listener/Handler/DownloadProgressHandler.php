<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CBase;
use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\ParamHandlerTrait;

class DownloadProgressHandler extends Handler
{
    use ParamHandlerTrait;
    use AjaxHandlerTrait;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->method = 'get';
        // $this->target = '';

        $this->name = 'DownloadProgress';
        $this->url = '';
        // $this->urlParam = [];
    }

    public function js()
    {
        $js = '';
        $dataAddition = $this->populateParamJson();

        $generatedUrl = $this->generatedUrl();
        $jsOptions = '{';
        $jsOptions .= "url:'" . $generatedUrl . "',";
        $jsOptions .= "method:'" . $this->method . "',";
        $jsOptions .= 'dataAddition:' . $dataAddition . ',';

        $jsOptions .= '}';

        $js .= '

            cresenity.downloadProgress(' . $jsOptions . ');

         ';

        return $js;
    }
}
