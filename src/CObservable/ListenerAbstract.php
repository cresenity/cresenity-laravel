<?php

namespace Cresenity\Laravel\CObservable;

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
                    $handler = new CObservable_Listener_Handler_ReloadHandler($this);

                    break;
                case 'reloadDataTable':
                    $handler = new CObservable_Listener_Handler_ReloadDataTableHandler($this);

                    break;
                case 'reloadElement':
                    $handler = new CObservable_Listener_Handler_ReloadElementHandler($this);

                    break;
                case 'dialog':
                    $handler = new CObservable_Listener_Handler_DialogHandler($this);

                    break;
                case 'append':
                    $handler = new CObservable_Listener_Handler_AppendHandler($this);

                    break;
                case 'prepend':
                    $handler = new CObservable_Listener_Handler_PrependHandler($this);

                    break;
                case 'submit':
                    $handler = new CObservable_Listener_Handler_SubmitHandler($this);

                    break;
                case 'ajaxSubmit':
                    $handler = new CObservable_Listener_Handler_AjaxSubmitHandler($this);

                    break;
                case 'remove':
                    $handler = new CObservable_Listener_Handler_RemoveHandler($this);

                    break;
                case 'downloadProgress':
                    $handler = new CObservable_Listener_Handler_DownloadProgressHandler($this);

                    break;
                case 'custom':
                    $handler = new CObservable_Listener_Handler_CustomHandler($this);

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
