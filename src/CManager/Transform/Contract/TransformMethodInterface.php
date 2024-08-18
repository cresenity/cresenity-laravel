<?php
namespace Cresenity\Laravel\CManager\Transform\Contract;

interface TransformMethodInterface
{
    public function transform($value, $arguments = []);
}
