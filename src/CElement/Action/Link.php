<?php

namespace Cresenity\Laravel\CElement\Element\Action;

use Cresenity\Laravel\CElement\Element\A;
use Cresenity\Laravel\CElement\Element\Contract\ActionableInterface;

class Link extends A implements ActionableInterface
{
    public static function factory($id = null)
    {
        // @phpstan-ignore-next-line
        return new static($id);
    }

    public function __construct()
    {
        $this->classes = [
            'btn',
            'btn-link'
        ];
    }

    protected function build()
    {
        parent::build();
    }
}
