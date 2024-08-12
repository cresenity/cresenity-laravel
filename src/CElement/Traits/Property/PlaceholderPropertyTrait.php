<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait Placeholder
{
    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var string
     */
    public $rawPlaceholder;

    /**
     * @param string     $placeholder
     * @param bool|array $lang
     *
     * @return $this
     */
    public function setPlaceholder($placeholder, $lang = true)
    {
        $this->rawPlaceholder = $placeholder;
        if ($lang !== false) {
            $placeholder = \c::__($placeholder, is_array($lang) ? $lang : []);
        }
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->rawPlaceholder;
    }

    /**
     * @return string
     */
    public function getTranslationPlaceholder()
    {
        return $this->placeholder;
    }
}
