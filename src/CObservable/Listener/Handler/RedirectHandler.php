<?php

use Cresenity\Laravel\CElement\Traits\Property\UrlPropertyTrait;
use Cresenity\Laravel\CObservable\Listener\Handler;

class RedirectHandler extends Handler
{
    use UrlPropertyTrait;

    public function js()
    {
        //parse url to normalize the value
        $url = $this->url;

        preg_match_all("/{(\w*)}/", $url, $matches);
        foreach ($matches[1] as $key => $match) {
            $url = str_replace('{' . $match . '}', "'+ cresenity.value('" . $match . "') +'", $url);
        }
        $js = "window.location.href = '" . $url . "';";

        return $js;
    }
}
