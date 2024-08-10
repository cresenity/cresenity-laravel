<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;
use Cresenity\Laravel\CElement\Factory;

class A extends Element
{
    protected $href;

    protected $target;

    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'a';
        $this->href = '';
        $this->target = '';
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public static function factory($id = null)
    {
        return Factory::create(static::class, $id);
    }

    /**
     * Set href attribute.
     *
     * @param string $href
     *
     * @return $this
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @param mixed $target
     *
     * @return $this
     */
    public function setTarget($target = '_blank')
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return $this
     */
    public function setTargetBlank()
    {
        return $this->setTarget('_blank');
    }

    protected function build()
    {
        parent::build();
        if (strlen($this->href) > 0) {
            $this->addAttr('href', $this->href);
        }
        if ($this->target) {
            $this->addAttr('target', $this->target);
        }
    }
}
