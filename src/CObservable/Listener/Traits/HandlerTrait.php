<?php

namespace Cresenity\Laravel\CObservable\Listener\Traits;

use Cresenity\Laravel\CObservable\Listener\Handler\AjaxHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\AjaxSubmitHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\AppendHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\CloseAllDialogHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\CloseDialogHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\CustomHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\DialogHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\DispatchWindowEventHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\DownloadProgressHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\EmitHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\PrependHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadDataTableHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadElementHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\RemoveHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\SubmitHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ToastHandler;
use Cresenity\Laravel\CObservable\Listener\Handler\ToggleActiveHandler;
use RedirectHandler;

trait HandlerTrait
{
    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler
     */
    public function addReloadHandler()
    {
        $handler = new ReloadHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\ReloadElementHandler
     */
    public function addReloadElementHandler()
    {
        $handler = new ReloadElementHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\ReloadDataTableHandler
     */
    public function addReloadDataTableHandler()
    {
        $handler = new ReloadDataTableHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\DispatchWindowEventHandler
     */
    public function addDispatchWindowEventHandler()
    {
        $handler = new DispatchWindowEventHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\AppendHandler
     */
    public function addAppendHandler()
    {
        $handler = new AppendHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\PrependHandler
     */
    public function addPrependHandler()
    {
        $handler = new PrependHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\DialogHandler
     */
    public function addDialogHandler()
    {
        $handler = new DialogHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\CloseDialogHandler
     */
    public function addCloseDialogHandler()
    {
        $handler = new CloseDialogHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\CloseAllDialogHandler
     */
    public function addCloseAllDialogHandler()
    {
        $handler = new CloseAllDialogHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\AjaxSubmitHandler
     */
    public function addAjaxSubmitHandler()
    {
        $handler = new AjaxSubmitHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\AjaxHandler
     */
    public function addAjaxHandler()
    {
        $handler = new AjaxHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\RemoveHandler
     */
    public function addRemoveHandler()
    {
        $handler = new RemoveHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\ToggleActiveHandler
     */
    public function addToggleActiveHandler()
    {
        $handler = new ToggleActiveHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\ToastHandler
     */
    public function addToastHandler()
    {
        $handler = new ToastHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\RedirectHandler
     */
    public function addRedirectHandler()
    {
        $handler = new RedirectHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\SubmitHandler
     */
    public function addSubmitHandler()
    {
        $handler = new SubmitHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\CustomHandler
     */
    public function addCustomHandler()
    {
        $handler = new CustomHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\EmitHandler
     */
    public function addEmitHandler()
    {
        $handler = new EmitHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\DownloadProgressHandler
     */
    public function addDownloadProgressHandler()
    {
        $handler = new DownloadProgressHandler($this);
        $this->handlers[] = $handler;

        return $handler;
    }
}
