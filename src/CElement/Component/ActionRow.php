<?php
namespace Cresenity\Laravel\CElement\Component;

use Opis\Closure\SerializableClosure;

class ActionRow extends Action
{
    protected $rowCallback;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->rowCallback = null;
    }

    public static function factory($id = null)
    {
        // @phpstan-ignore-next-line
        return new static($id);
    }

    public function withRowCallback($callback)
    {
        $this->rowCallback = new SerializableClosure($callback);

        return $this;
    }

    public function applyRowCallback($row)
    {
        if ($this->rowCallback && $this->rowCallback instanceof SerializableClosure) {
            $this->rowCallback->__invoke($this, $row);
        }

        return $this;
    }
}
