<?php

namespace Cresenity\Laravel\CElement\Element\Depends;

use Cresenity\Laravel\Traits\HasOptions;
use Illuminate\Support\Arr;
use Opis\Closure\SerializableClosure;

/**
 * @see CElement_Element_Div
 * @see CElement_FormInput_Select
 * @see CElement_FormInput_SelectSearch
 */
class DependsOn
{
    use HasOptions;

    /**
     * @var \Cresenity\Laravel\CElement\Element\Depends\Selector
     */
    protected $selector;

    protected $resolver;

    public function __construct($selector, $resolver, $options = [])
    {
        $this->options = $options;

        $this->setResolver($resolver);
        $this->selector = new Selector(Arr::wrap($selector));
    }

    public function addSelector($selector)
    {
        $this->selector->addSelector($selector);

        return $this;
    }

    public function setSelector($selector)
    {
        $this->selector->setSelectors($selector);

        return $this;
    }

    public function setResolver($resolver)
    {
        $this->resolver = new SerializableClosure($resolver);

        return $this;
    }

    public function getSelector()
    {
        return $this->selector;
    }

    public function getResolver()
    {
        return $this->resolver;
    }

    public function getThrottle()
    {
        return $this->getOption('throttle', 100);
    }

    public function getBlock()
    {
        return $this->getOption('block', true);
    }
}
