<?php

use Illuminate\Support\Collection;

class CRenderable extends CObject {
    use CTrait_Compat_Renderable;

    /**
     * Renderable Child Array.
     *
     * @var CRenderable[]
     */
    protected $renderable;

    protected $additionalJs;

    protected $visibility;

    protected $parent;

    protected $wrapper;

    protected function __construct($id = '') {
        parent::__construct($id);

        $this->renderable = new Collection();
        $this->wrapper = $this;
        $this->additionalJs = '';
        $this->visibility = true;
        $this->parent = null;
    }

    public function childCount() {
        return count($this->renderable);
    }

    public function childs() {
        return $this->renderable;
    }

    public function setParent($parent) {
        $this->parent = &$parent;

        return $this;
    }

    public function setVisibility($bool) {
        $this->visibility = $bool;
    }

    /**
     * Apply call method or set property of all childs of this object.
     *
     * @param string            $key
     * @param mixed             $value
     * @param null|string|array $className
     *
     * @return $this
     */
    public function apply($key, $value, $className = null) {
        if ($className !== null) {
            $className = carr::wrap($className);
        }
        foreach ($this->renderable as $r) {
            if ($className === null || in_array($r->className(), $className)) {
                if (method_exists($r, $key)) {
                    $r->$key($value);
                } else {
                    $r->$key = $value;
                }
            }
        }

        return $this;
    }

    public function add($renderable) {
        if ($renderable instanceof CRenderable) {
            $renderable->setParent($this);
        }

        $this->wrapper->renderable[] = $renderable;

        $this->dispatchEvent(CApp_Event::createEventOnRenderableAdded($renderable));

        return $this;
    }

    public function addJs($js) {
        $this->additionalJs .= $js;

        return $this;
    }

    public function clear() {
        foreach ($this->renderable as $r) {
            if ($r instanceof CRenderable) {
                $r->clear();
            }
            if ($r instanceof CObject) {
                CObserver::instance()->remove($r);
            }
        }
        $this->renderable = [];

        return $this;
    }

    public function parentHtml() {
        return parent::html();
    }

    public function parentJs() {
        return parent::js();
    }

    public function html($indent = 0) {
        if (!$this->visibility) {
            return '';
        }
        $html = new CStringBuilder();
        $html->setIndent($indent);
        $html->incIndent();
        foreach ($this->renderable as $r) {
            $child = null;

            if ($r instanceof CRenderable) {
                if (!$r->visibility) {
                    continue;
                }

                $r = $r->html($html->getIndent());
            }
            if ($r instanceof CInterface_Renderable) {
                $r = $r->render();
            }

            /**
             * \Stringable available on PHP 8.
             */
            if ($r instanceof \Stringable) {
                $r = $r->__toString();
            }

            if (is_object($r) || is_array($r)) {
                $dumper = new CDebug_Dumper();
                $r = $dumper->getDump($r);
            }

            $html->append($r);
        }
        $html->decIndent();

        return $html->text();
    }

    public function js($indent = 0) {
        if (!$this->visibility) {
            return '';
        }
        $js = new CStringBuilder();
        $js->setIndent($indent);
        foreach ($this->renderable as $r) {
            if ($r instanceof CRenderable) {
                $js->append($r->js($js->getIndent()));
            }
        }
        $js->append($this->additionalJs);

        return $js->text();
    }

    public function json() {
        $data = [];
        $data['html'] = cmsg::flash_all() . $this->html();
        $data['js'] = base64_encode($this->js());
        $data['js_require'] = CManager::asset()->getAllJsFileUrl();
        $data['css_require'] = CManager::asset()->getAllCssFileUrl();

        return json_encode($data);
    }

    public function regenerateId($recursive = false) {
        parent::regenerateId();
        if ($recursive) {
            foreach ($this->renderable as $r) {
                if ($r instanceof CRenderable) {
                    $r->regenerateId($recursive);
                }
            }
        }
    }

    public function toArray() {
        $data = parent::toArray();
        $data['visibility'] = $this->visibility;
        foreach ($this->renderable as $r) {
            if ($r instanceof CRenderable) {
                $arrays[] = $r->toArray();
            } else {
                $arrays[] = $r;
            }
        }

        if (!empty($arrays)) {
            $data['children'] = $arrays;
        }

        return $data;
    }

    /**
     * Fire the given event if possible.
     *
     * @param mixed $event
     *
     * @return void
     */
    protected function dispatchEvent($event) {
        $this->getEvent()->dispatch($event);
    }

    /**
     * @return CEvent_Dispatcher;
     */
    public function getEvent() {
        return CEvent::dispatcher();
    }

    public function &getParent() {
        return $this->parent;
    }

    public static function renderStyle(array $styles) {
        if ($styles == null) {
            return '';
        }
        $ret = '';
        foreach ($styles as $k => $v) {
            $ret .= $k . ':' . $v . ';';
        }

        return $ret;
    }

    public function detach() {
        $this->parent = null;

        return $this;
    }
}
