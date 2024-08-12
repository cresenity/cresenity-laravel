<?php

namespace Cresenity\Laravel\CObservable\Listener;

use Cresenity\Laravel\CObservable\Listener\Handler\Contract\ParamableInterface;
use Cresenity\Laravel\CObservable\ListenerAbstract;

abstract class Handler implements ParamableInterface
{
    const TYPE_REMOVE = 'remove';

    const TYPE_RELOAD = 'reload';

    const TYPE_SUBMIT = 'submit';

    const TYPE_DIALOG = 'dialog';

    const TYPE_EMPTY = 'empty';

    const TYPE_CUSTOM = 'custom';

    const TYPE_APPEND = 'append';

    const TYPE_PREPEND = 'prepend';

    protected $name;

    protected $handlers;

    protected $driver;

    protected $listener;

    protected $handlerListeners = [];

    /**
     * Event from listener
     *
     * @var string
     */
    protected $event;

    /**
     * Id element of owner this event listener
     *
     * @var string
     */
    protected $owner;

    /**
     * Handler Params
     *
     * @var array
     */
    protected $params;

    public function __construct(ListenerAbstract $listener)
    {
        $this->listener = $listener;
        $this->owner = $this->listener->getOwner();
        $this->event = $this->listener->getEvent();
        $this->params = [];
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function haveListener($event)
    {
        return isset($this->handlerListeners[$event]);
    }

    public function getListener($event)
    {
        if ($this->haveListener($event)) {
            return $this->handlerListeners[$event];
        }
        return null;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    abstract public function js();
}
