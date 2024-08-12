<?php

namespace Cresenity\Laravel\CObservable;

use Cresenity\Laravel\CObservable\Listener\Handler\AjaxSubmitHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\AppendHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\CustomHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\DialogHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\DownloadProgressHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\PrependHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadDataTableHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadElementHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\RemoveHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\SubmitHandler;
use Cresenity\Laravel\CObservable\Listener\Traits\HandlerTrait;

abstract class ListenerAbstract
{
    use HandlerTrait;

    protected $owner;

    protected $handlers;

    protected $event;

    protected $eventParameters = [];

    public function __construct($owner)
    {
        $this->owner = $owner;
        $this->handlers = [];
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function owner()
    {
        return $this->getOwner();
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
        //we set all handler owner too
        foreach ($this->handlers as $handler) {
            $handler->setOwner($owner);
        }

        return $this;
    }

    public function handlers()
    {
        return $this->handlers;
    }

    /**
     * @param type $param
     */
    public function setHandlerParam($param)
    {
        foreach ($this->handlers as $handler) {
            $handler->setParams($param);
        }
    }

    /**
     * @param string $handlerName
     *
     * @return CObservable_Listener_Handler
     */
    public function addHandler($handlerName)
    {
        $handler = $handlerName;
        if (is_string($handler)) {
            switch ($handler) {
                case 'reload':
                    $handler = new ReloadHandler($this);

                    break;
                case 'reloadDataTable':
                    $handler = new ReloadDataTableHandler($this);

                    break;
                case 'reloadElement':
                    $handler = new ReloadElementHandler($this);

                    break;
                case 'dialog':
                    $handler = new DialogHandler($this);

                    break;
                case 'append':
                    $handler = new AppendHandler($this);

                    break;
                case 'prepend':
                    $handler = new PrependHandler($this);

                    break;
                case 'submit':
                    $handler = new SubmitHandler($this);

                    break;
                case 'ajaxSubmit':
                    $handler = new AjaxSubmitHandler($this);

                    break;
                case 'remove':
                    $handler = new RemoveHandler($this);

                    break;
                case 'downloadProgress':
                    $handler = new DownloadProgressHandler($this);

                    break;
                case 'custom':
                    $handler = new CustomHandler($this);

                    break;
                default:
                    if (class_exists($handler)) {
                        $handler = new $handler($this);
                    } else {
                        throw new Exception('Handler : ' . $handlerName . ' not defined');
                    }

                    break;
            }
        }
        $this->handlers[] = $handler;

        return $handler;
    }
}
