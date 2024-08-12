<?php

namespace Cresenity\Laravel\CView\Component;

use Cresenity\Laravel\CManager\Icon\IconHtml;
use \Illuminate\View\Component;

class IconComponent extends Component
{
    /**
     * @var null|string
     */
    public $class;

    /**
     * @var string
     */
    public $width;

    /**
     * @var string
     */
    public $height;

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $fill;

    /**
     * @var string
     */
    public $id;

    /**
     * Icon tag.
     *
     * @var string
     */
    private $path;

    /**
     * Create a new component instance.
     *
     * @param string      $path
     * @param null|string $id
     * @param null|string $class
     * @param string      $width
     * @param string      $height
     * @param string      $role
     * @param string      $fill
     */
    public function __construct(
        $path,
        $id = null,
        $class = null,
        $width = '1em',
        $height = '1em',
        $role = 'img',
        $fill = 'currentColor'
    ) {
        $this->path = $path;
        $this->id = $id;
        $this->class = $class;
        $this->width = $width;
        $this->height = $height;
        $this->role = $role;
        $this->fill = $fill;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Cresenity\Laravel\CManager\Icon\IconHtml
     */
    public function render()
    {
        $icon = \c::manager()->icon()->loadFile($this->path);

        $content = $this->setAttributes($icon);

        return new IconHtml($content);
    }

    /**
     * @param null|string $icon
     *
     * @return string
     */
    private function setAttributes($icon)
    {
        if ($icon === null) {
            return '';
        }

        $dom = new \DOMDocument();
        $dom->loadXML($icon);

        /** @var \DOMElement $item */
        $item = \c::collect($dom->getElementsByTagName('svg'))->first();

        \c::collect($this->data())
            ->except('attributes')
            ->filter(function ($value) {
                return $value !== null && is_string($value);
            })
            ->each(function ($value, $key) use ($item) {
                $item->setAttribute($key, $value);
            });

        return $dom->saveHTML();
    }
}
