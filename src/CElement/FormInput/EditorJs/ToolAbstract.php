<?php

namespace Cresenity\Laravel\CElement\Element\FormInput\EditorJs;

abstract class ToolAbstract
{
    protected $enabled;

    public function enable()
    {
        $this->enabled = true;
    }

    public function disable()
    {
        $this->enabled = false;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    abstract public function getConfig();
}
