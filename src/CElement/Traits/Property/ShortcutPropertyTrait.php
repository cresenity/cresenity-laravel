<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait ShortcutPropertyTrait
{
    protected $shortcut;

    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }

    public function getShortcut()
    {
        return $this->shortcut;
    }
}
