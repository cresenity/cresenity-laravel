<?php

namespace Cresenity\Laravel\CElement\Element;

use Cresenity\Laravel\CElement\Element;

class Span extends Element
{
    public function __construct($id = '')
    {
        parent::__construct($id);
        $this->tag = 'span';
    }

    public static function factory($id = null)
    {
        /** @phpstan-ignore-next-line */
        return new static($id);
    }

    // @codingStandardsIgnoreStart
    public function set_col($col = null)
    {
        // @codingStandardsIgnoreEnd
        return $this;
    }
}
