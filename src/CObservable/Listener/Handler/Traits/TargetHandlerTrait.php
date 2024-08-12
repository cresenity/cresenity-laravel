<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler\Traits;

use Cresenity\Laravel\CObservable;
use Cresenity\Laravel\CObservable\Listener\Handler\ReloadHandler;
use Cresenity\Laravel\CRenderable;

trait TargetHandlerTrait
{
    /**
     * Id of handler targeted renderable
     *
     * @var string
     */
    protected $target;

    public function setTarget($target)
    {
        if ($target instanceof CObservable) {
            if (get_class($this) == ReloadHandler::class) {
                if ($target->haveReloadHandler()) {
                    $reloadHandler = $target->reloadHandler();
                    if (\c::hasTrait($this, AjaxHandlerTrait::class)
                        && \c::hasTrait($reloadHandler, AjaxHandlerTrait::class)
                    ) {
                        if (strlen($this->url) == 0) {
                            $this->url = $reloadHandler->getUrl();
                        }
                    }
                }
            }
        }
        if ($target instanceof CRenderable) {
            $target = $target->id();
        }
        $this->target = $target;

        return $this;
    }
}
