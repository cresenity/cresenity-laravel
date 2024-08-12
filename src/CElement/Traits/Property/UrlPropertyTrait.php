<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait UrlPropertyTrait
{
    protected $url;

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function haveUrl()
    {
        return strlen($this->url) > 0;
    }
}
