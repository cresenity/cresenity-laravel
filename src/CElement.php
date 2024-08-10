<?php
namespace Cresenity\Laravel;

abstract class CElement extends CObservable
{

    /**
     * Class css for this element.
     *
     * @var array
     */
    protected $classes;

    protected $tag;

    protected $attr;

    protected $custom_css;

    protected $bootstrap;

    protected $before;

    protected $after;

    public function __construct($id = null, $tag = 'div')
    {
        parent::__construct($id);

        $this->classes = [];
        $this->attr = [];
        $this->custom_css = [];

        $this->tag = $tag;
    }

    /**
     * Set custom css style.
     *
     * @param string $key
     * @param string $val
     *
     * @return $this
     */
    public function customCss($key, $val)
    {
        $this->custom_css[$key] = $val;

        return $this;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * Add class attribute value for the element.
     *
     * @param string|array $classes
     *
     * @return $this
     */
    public function addClass($classes)
    {
        if (is_array($classes)) {
            foreach ($classes as $class) {
                $this->addClass($class);
            }
        }

        $classes = (string) $classes;
        $classes = c::collect(explode(' ', $classes))->filter()->all();

        $this->classes = carr::merge($this->classes, $classes);

        return $this;
    }

    /**
     * Remove class attribute value for the element.
     *
     * @param string|array $classes
     *
     * @return $this
     */
    public function removeClass($classes)
    {
        if (is_array($classes)) {
            foreach ($classes as $class) {
                $this->removeClass($class);
            }
        }
        $classes = (string) $classes;
        $classes = c::collect(explode(' ', $classes))->filter()->all();

        foreach ($classes as $class) {
            if (($key = array_search($class, $this->classes)) !== false) {
                unset($this->classes[$key]);
            }
        }

        return $this;
    }

    /**
     * Get class as string.
     *
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Remove attr.
     *
     * @param string $k
     *
     * @return $this
     */
    public function removeAttr($k)
    {
        if (isset($this->attr[$k])) {
            unset($this->attr[$k]);
        }

        return $this;
    }

    /**
     * Alias for removeAttr.
     *
     * @param string $k
     *
     * @return $this
     */
    public function deleteAttr($k)
    {
        return $this->removeAttr($k);
    }

    /**
     * Set attribute for element.
     *
     * @param string|array $k
     * @param string       $v
     *
     * @return $this
     */
    public function setAttr($k, $v = null)
    {
        if (is_array($k)) {
            return $this->setAttrFromArray($k);
        }
        if ($k == 'class') {
            return $this->addClass($v);
        }

        // if ($k == 'style') {
        //     foreach (explode(';', $v) as $attr) {
        //         if (strlen(trim($attr)) > 0) { // for missing semicolon on last element, which is legal
        //             list($name, $value) = explode(':', $attr);
        //             $this->customCss($name, $value);
        //         }
        //     }

        //     return $this;
        // }

        $this->attr[$k] = $v;

        return $this;
    }

    /**
     * Set attribute for element with array.
     *
     * @param array $arr
     *
     * @return $this
     */
    public function setAttrFromArray($arr)
    {
        foreach ($arr as $k => $v) {
            $this->setAttr($k, $v);
        }

        return $this;
    }

    /**
     * Alias for setAttr.
     *
     * @param string|array $k
     * @param string       $v
     *
     * @return $this
     */
    public function addAttr($k, $v)
    {
        return $this->setAttr($k, $v);
    }

    public function getAttr($k)
    {
        if (isset($this->attr[$k])) {
            return $this->attr[$k];
        }

        return null;
    }

    public function pretag()
    {
        return '<' . $this->tag . '>';
    }

    public function posttag()
    {
        return '</' . $this->tag . '>';
    }

    public function toArray()
    {
        $data = parent::toArray();
        if (!empty($this->classes)) {
            $data['attr']['class'] = implode(' ', $this->classes);
        }
        $data['attr']['id'] = $this->id;

        $data['tag'] = $this->tag;

        return $data;
    }

    protected function htmlChild($indent = 0)
    {
        return parent::html($indent);
    }

    protected function jsChild($indent = 0)
    {
        return parent::js($indent);
    }

    public function __toString()
    {
        $return = '<h3> HTML </h3>'
                . '<pre>'
                . '<code>'
                . htmlspecialchars($this->html())
                . '</code>'
                . '</pre>';
        $return .= '<h3> JS </h3>'
                . '<pre>'
                . '<code>'
                . htmlspecialchars($this->js())
                . '</code>'
                . '</pre>';

        return $return;
    }

    /**
     * @return CElement_PseudoElement
     */
    public function before()
    {
        if ($this->before == null) {
            $this->before = CElement_PseudoElement::factory();
        }

        return $this->before;
    }

    /**
     * @return CElement_PseudoElement
     */
    public function after()
    {
        if ($this->after == null) {
            $this->after = CElement_PseudoElement::factory();
        }

        return $this->after;
    }
}
