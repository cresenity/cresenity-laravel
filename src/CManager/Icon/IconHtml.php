<?php

namespace Cresenity\Laravel\CManager\Icon;

use Illuminate\Contracts\Support\Htmlable;

class IconHtml implements Htmlable
{
    /**
     * @var null|string
     */
    protected $content;

    /**
     * Icon constructor.
     *
     * @param null|string $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;
    }

    /**
     * @return null|string
     */
    public function toHtml()
    {
        return $this->content;
    }
}
