<?php

namespace Cresenity\Laravel\CElement;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Helper
{
    public static function getClasses($classes)
    {
        if (is_string($classes)) {
            return \c::collect(explode(' ', $classes))->filter(function ($class) {
                return !\c::blank($class);
            })->all();
        }
        if ($classes instanceof Collection) {
            return $classes->filter(function ($class) {
                return !\c::blank($class);
            })->all();
        }
        if ($classes instanceof Arrayable) {
            return $classes->toArray();
        }
        if (is_array($classes)) {
            return $classes;
        }
        return [];
    }
}
