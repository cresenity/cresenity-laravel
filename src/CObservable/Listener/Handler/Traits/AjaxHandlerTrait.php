<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler\Traits;

use Cresenity\Laravel\CAjax;
use Cresenity\Laravel\CBase;
use Cresenity\Laravel\CObservable\Listener\Pseudo\CompleteListener;
use Cresenity\Laravel\CObservable\Listener\Pseudo\ErrorListener;
use Cresenity\Laravel\CObservable\Listener\Pseudo\SuccessListener;

trait AjaxHandlerTrait
{
    protected $method;

    protected $callback;

    /**
     * Url for ajax handler type.
     *
     * @var string
     */
    protected $url;

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function setCallback($callback)
    {
        $this->callback = \c::toSerializableClosure($callback);

        return $this;
    }

    public function generatedUrl()
    {
        $link = $this->url;

        if (strlen($link) == 0) {
            $callback = $this->callback;

            $ajaxUrl = CAjax::createMethod()->setType('AjaxHandler')
                ->setData('json', $this->content->json())
                ->setData('callback', $callback)
                ->makeUrl();

            $link = $ajaxUrl;
        }

        $link = CBase::createStringParamable($link, $this->params)->get();

        return $link;
    }

    /**
     * Add Ajax Success Listener.
     *
     * @return Cresenity\Laravel\CObservable\Listener\Pseudo\SuccessListener
     */
    public function onSuccessListener()
    {
        if (!isset($this->handlerListeners['ajaxSuccess'])) {
            $this->handlerListeners['ajaxSuccess'] = new SuccessListener($this);
        }

        return $this->handlerListeners['ajaxSuccess'];
    }

    /**
     * Add Ajax Error Listener.
     *
     * @return Cresenity\Laravel\CObservable\Listener\Pseudo\ErrorListener
     */
    public function onErrorListener()
    {
        if (!isset($this->handlerListeners['ajaxError'])) {
            $this->handlerListeners['ajaxError'] = new ErrorListener($this);
        }

        return $this->handlerListeners['ajaxError'];
    }

    /**
     * Add Ajax Complete Listener.
     *
     * @return Cresenity\Laravel\CObservable\Listener\Pseudo\CompleteListener
     */
    public function onCompleteListener()
    {
        if (!isset($this->handlerListeners['ajaxComplete'])) {
            $this->handlerListeners['ajaxComplete'] = new CompleteListener($this);
        }

        return $this->handlerListeners['ajaxComplete'];
    }

    /**
     * Check have success listener.
     *
     * @return bool
     */
    public function haveSuccessListener()
    {
        return $this->haveListener('ajaxSuccess');
    }

    /**
     * Check have error listener.
     *
     * @return bool
     */
    public function haveErrorListener()
    {
        return $this->haveListener('ajaxError');

        return isset($this->handlerListeners['ajaxError']);
    }

    /**
     * Check have complete listener.
     *
     * @return bool
     */
    public function haveCompleteListener()
    {
        return $this->haveListener('ajaxComplete');
    }

    public function getCompleteListener()
    {
        return $this->getListener('ajaxComplete');
    }

    public function getSuccessListener()
    {
        return $this->getListener('ajaxSuccess');
    }

    public function getErrorListener()
    {
        return $this->getListener('ajaxError');
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
