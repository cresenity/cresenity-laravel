<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait AutoCompletePropertyTrait
{
    /**
     * @var string
     */
    public $autoComplete;

    /**
     * @param string|bool $bool
     *
     * @return $this
     */
    public function setAutoComplete($bool)
    {
        if (is_string($bool)) {
            $bool = in_array($bool, ['on', 'yes', 'ok', 'y']) ? true : false;
        }
        $this->autoComplete = $bool;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoComplete()
    {
        return $this->autoComplete;
    }

    /**
     * @return $this
     */
    public function setAutoCompleteOn()
    {
        $this->autoComplete = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAutoCompleteOff()
    {
        $this->autoComplete = false;
        return $this;
    }
}
