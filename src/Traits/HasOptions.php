<?php
namespace Cresenity\Laravel\Traits;

use Illuminate\Support\Arr;

trait HasOptions
{
    /**
     * @var array
     */
    protected $options;

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function setOption($key, $option)
    {
        Arr::set($this->options, $key, $option);

        return $this;
    }

    public function getOption($key, $defaultValue = null)
    {
        return Arr::get($this->options, $key, $defaultValue);
    }

    public function mergeAsDefault(array $newDefaults)
    {
        $this->options = array_merge($newDefaults, $this->options);

        return $this;
    }

    public function merge(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }
}
