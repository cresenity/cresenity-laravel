<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CBase;
use Cresenity\Laravel\CObservable\Listener\Handler;

class DispatchWindowEventHandler extends Handler
{
    protected $method;

    protected $parameters;

    public function __construct($listener)
    {
        parent::__construct($listener);

        $this->name = 'DispatchWindowEvent';
    }

    /**
     * Set Method.
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Add Emit Parameter.
     *
     * @param mixed $param
     *
     * @return $this
     */
    public function addParameter($param)
    {
        $this->parameters[] = $param;
    }

    /**
     * Set Emit Parameters.
     *
     * @param array $params
     *
     * @return $this
     */
    public function setParameters(array $params)
    {
        $this->parameters = $params;
    }

    /**
     * Apply Param Array.
     *
     * @param array $array
     *
     * @return array
     */
    protected function applyArrayParams(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->applyArrayParams($value);
            }
            if (is_string($value)) {
                $array[$key] = CBase::createStringParamable($value, $this->params)->get();
            }
        }

        return $array;
    }

    public function js()
    {
        $js = '';
        if ($this->method) {
            $js .= "window.cresenity.dispatchWindowEvent('" . \c::e($this->method) . "'";

            if (is_array($this->parameters) && count($this->parameters) > 0) {
                $parameters = $this->applyArrayParams($this->parameters);
                $js .= ',' . json_encode($parameters);
            }
            $js .= ');';
        }

        return $js;
    }
}
