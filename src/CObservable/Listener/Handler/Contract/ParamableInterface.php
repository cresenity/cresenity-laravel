<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler\Contract;

interface ParamableInterface
{
    public function getParams();

    public function setParams(array $params);

    public function addParam($key, $value);
}
