<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\SelectorHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class ToggleActiveHandler extends Handler
{
    use TargetHandlerTrait;
    use SelectorHandlerTrait;

    /**
     * @var string
     */
    protected $itemsSelector;

    /**
     * @var string
     */
    protected $toggleClass;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->target = $this->owner;
        $this->name = 'ToggleActive';
        $this->itemsSelector = null;
        $this->toggleClass = 'active';
    }

    public function setItemsSelector($selector)
    {
        $this->itemsSelector = $selector;

        return $this;
    }

    public function setToggleClass($class)
    {
        $this->toggleClass = $class;

        return $this;
    }

    public function js()
    {
        $js = '';

        if ($this->itemsSelector) {
            $js .= "jQuery('" . $this->itemsSelector . "').removeClass('" . $this->toggleClass . "');";
        } else {
            $js .= "jQuery('" . $this->getSelector() . "').parent().children().removeClass('" . $this->toggleClass . "');";
        }
        $js .= "jQuery('" . $this->getSelector() . "').addClass('" . $this->toggleClass . "');";

        return $js;
    }
}
