<?php
namespace Cresenity\Laravel\CManager\Transform\Method;

use Cresenity\Laravel\CManager\Transform\Contract\TransformMethodInterface;
use Opis\Closure\SerializableClosure;

class ClosureMethod implements TransformMethodInterface
{
    /**
     * @var Closure|\Opis\Closure\SerializableClosure
     */
    protected $closure;

    public function __construct($closure)
    {
        $this->closure = $closure;
        if (!($this->closure instanceof SerializableClosure)) {
            $this->closure = new SerializableClosure($closure);
        }
    }

    public function transform($value, $arguments = [])
    {
        return $this->closure->__invoke($value, $arguments);
    }
}
