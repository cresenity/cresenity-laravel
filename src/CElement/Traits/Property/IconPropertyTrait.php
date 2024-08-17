<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

use Cresenity\Laravel\CView\Component\IconComponent;

trait IconPropertyTrait
{
    protected $icon;

    protected $originalIcon;

    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->originalIcon = $icon;
        if (strpos($icon, 'fa-') === false && strpos($icon, 'ion-') === false && strpos($icon, 'ti-') === false && strpos($icon, 'lnr-') === false) {
            $icon = $icon . ' icon-' . $icon;
        }

        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    public function getIconHtml()
    {
        if (strlen($this->originalIcon) == 0) {
            return '';
        }
        if (strpos($this->originalIcon, '.') !== false) {
            if ($icon = \c::manager()->icon()->loadFile($this->originalIcon)) {
                $component = new IconComponent($this->originalIcon);

                $icon = $component->render()->toHtml();

                return '<i class="capp-icon" data-icon="' . $this->originalIcon . '">' . $icon . '</i> ';
            }
        }
        $iconPrefix = \c::theme('icon.prefix', 'icon icon-');

        return '<i class="capp-icon ' . $iconPrefix . $this->getIcon() . ' ' . $this->getIcon() . '"></i> ';
    }
}