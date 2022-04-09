<?php

use Illuminate\Support\Traits\Tappable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Conditionable;

class CObject {
    use Macroable;
    use Tappable;
    use Conditionable;

    protected $id;

    protected $domain = '';

    protected function __construct($id = '') {
        $observer = CObserver::instance();
        if ($id == '') {
            $id = spl_object_hash($this);
        }

        $this->id = $id;
        $this->domain = CF::domain();
        $observer->add($this);
    }

    public function regenerateId() {
        $this->id = CObserver::instance()->newId();
    }

    public function id() {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function className() {
        return get_class($this);
    }

    public function isUseTrait($trait) {
        $traits = c::classUsesRecursive($this->className());

        return isset($traits[$trait]);
    }

    /**
     * @deprecated since 1.2
     *
     * @return string
     */
    public function domain() {
        return $this->domain;
    }

    public function toArray() {
        $data = [
            'id' => $this->id,
        ];

        return $data;
    }
}
