<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait TooltipPropertyTrait
{
    protected $tooltip;

    /**
     * @return CElement_Component_Tooltip;
     */
    public function tooltip()
    {
        if ($this->tooltip == null) {
            $this->tooltip = CElement_Component_Tooltip::factory($this->id . '-tooltip');
        }

        return $this->tooltip;
    }

    public function setTooltipText($text)
    {
        $this->tooltip()->setText($text);

        return $this;
    }
}
