<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler\Traits;

trait SelectorHandlerTrait
{
    /**
     * Query selector of handler targeted renderable
     *
     * @var string
     */
    protected $selector;

    public function setSelector($selector)
    {
        $this->selector = $selector;

        return $this;
    }

    public function getSelector()
    {
        if ($this->selector != null) {
            return $this->selector;
        }

        if (\c::hasTrait($this, TargetHandlerTrait::class)) {
            if (strlen($this->target) > 0) {
                return '#' . $this->target;
            }
        }
        return '#';
    }
}
