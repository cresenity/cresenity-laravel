<?php

namespace Cresenity\Laravel\CElement\Traits\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler;

/**
 * Description of Reloadable.
 *
 * @author Hery
 */
trait ReloadHandlerTrait
{
    /**
     * Reload Handler.
     *
     * @var \Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler
     */
    protected $reloadHandler;

    protected $reloadHandlers = [];

    /**
     * @return \Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler
     */
    public function reloadHandler()
    {
        if ($this->reloadHandler == null) {
            $listener = $this->addListener('ready');

            $this->reloadHandler = new ReloadHandler($listener);
            $this->reloadHandler->setSelector('#' . $this->id());
            $listener->addHandler($this->reloadHandler);
            $this->reloadHandlers[] = $this->reloadHandler;
        }

        return $this->reloadHandler;
    }

    protected function bootBuildReloadHandler()
    {
        if ($this->reloadHandler) {
            $attributes = $this->reloadHandler->toAttributeArray();
            foreach ($attributes as $key => $value) {
                $this->setAttr('data-' . cstr::snake($key, '-'), c::e($value));
            }
        }
    }

    public function haveReloadHandler()
    {
        return $this->reloadHandler !== null;
    }
}
