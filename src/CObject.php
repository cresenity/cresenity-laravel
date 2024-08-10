<?php
namespace Cresenity\Laravel;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;

class CObject
{
    use Macroable;
    use Tappable;
    use Conditionable;

    protected $id;

    protected function __construct($id = null)
    {
        $observer = Observer::instance();
        if ($id == null) {
            $id = 'c' . spl_object_hash($this);
        }

        $this->id = $id;
        $observer->add($this);
    }

    public function regenerateId()
    {
        $this->id = Observer::instance()->newId();
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function className()
    {
        return get_class($this);
    }

    public function isUseTrait($trait)
    {
        $traits = \c::classUsesRecursive($this->className());

        return isset($traits[$trait]);
    }

    /**
     * @deprecated since 1.2
     *
     * @return string
     */
    public function domain()
    {
        return $this->domain;
    }

    public function toArray()
    {
        $data = [
            'id' => $this->id,
        ];

        return $data;
    }
}