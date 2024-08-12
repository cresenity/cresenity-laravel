<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait ApplyJsPropertyTrait
{
    /**
     * @var string
     */
    protected $applyJs;

    /**
     * @param string $applyJs
     *
     * @return $this
     */
    public function setApplyJs($applyJs)
    {
        $this->applyJs = $applyJs;

        return $this;
    }

    /**
     * @return $this
     */
    public function setApplyJsSelect2()
    {
        return $this->setApplyJs('select2');
    }

    /**
     * @return string
     */
    public function getApplyJs()
    {
        return $this->applyJs;
    }
}
