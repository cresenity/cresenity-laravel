<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;
use Cresenity\Laravel\CElement\Traits\Handler\ReloadHandlerTrait;
use Cresenity\Laravel\CElement\Traits\Property\DependsOnPropertyTrait;
use Cresenity\Laravel\CStringBuilder;

class Div extends Element
{
    use ReloadHandlerTrait;
    use DependsOnPropertyTrait;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->tag = 'div';
    }

    public static function factory($id = null)
    {
        // @phpstan-ignore-next-line
        return new static($id);
    }

    protected function build()
    {
        parent::build();
        $this->bootBuildReloadHandler();
    }

    public function js($indent = 0)
    {
        $js = new CStringBuilder();

        $js->append(parent::js());
        $js->append($this->getDependsOnContentJavascript());

        return $js->text();
    }
}
