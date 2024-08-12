<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait ContentPropertyTrait
{
    /**
     * @var string
     */
    protected $content;

    /**
     * Set content of element
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content of element
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
