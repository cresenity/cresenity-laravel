<?php

namespace Cresenity\Laravel\CElement\Element\FormInput;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\Property\PlaceholderPropertyTrait;
use Cresenity\Laravel\CManager;

class AutoNumeric extends FormInput
{
    use PlaceholderPropertyTrait;

    protected $decimalDigit = 0;

    protected $thousandSeparator = ',';

    protected $decimalSeparator = '.';

    protected $minValue = null;

    protected $maxValue = null;

    public function __construct($id)
    {
        parent::__construct($id);

        $this->type = 'text';
        $this->placeholder = '';
        $this->value = '0';
        $this->decimalDigit = \c::formatter()->getDecimalDigit();
        $this->thousandSeparator = \c::formatter()->getThousandSeparator();
        $this->decimalSeparator = \c::formatter()->getDecimalSeparator();
        $this->addClass('form-control');

        if (!CManager::asset()->module()->isRegisteredModule('auto-numeric')) {
            CManager::asset()->module()->registerRunTimeModule('auto-numeric');
        }
    }

    public static function factory($id = null)
    {
        return new AutoNumeric($id);
    }

    public function setDecimalDigit($digit)
    {
        $this->decimalDigit = $digit;

        return $this;
    }

    public function setThousandSeparator($separator)
    {
        $this->thousandSeparator = $separator;

        return $this;
    }

    public function setDecimalSeparator($separator)
    {
        $this->decimalSeparator = $separator;

        return $this;
    }

    /**
     * @param float $maxValue
     *
     * @return $this
     */
    public function setMaxValue($maxValue)
    {
        $this->maxValue = $maxValue;

        return $this;
    }

    /**
     * @param float $minValue
     *
     * @return $this
     */
    public function setMinValue($minValue)
    {
        $this->minValue = $minValue;

        return $this;
    }

    protected function build()
    {
        $this->setAttr('type', $this->type);
        $this->setAttr('value', $this->value);
        if (!isset($this->attr['data-m-dec'])) {
            $this->setAttr('data-m-dec', $this->decimalDigit);
        }
        if (!isset($this->attr['data-a-sep'])) {
            $this->setAttr('data-a-sep', $this->thousandSeparator);
        }
        if (!isset($this->attr['data-a-dec'])) {
            $this->setAttr('data-a-dec', $this->decimalSeparator);
        }
        if ($this->placeholder) {
            $this->setAttr('placeholder', $this->placeholder);
        }

        if ($this->maxValue !== null) {
            $this->setAttr('data-v-max', $this->maxValue);
        }
        if ($this->minValue !== null) {
            $this->setAttr('data-v-min', $this->minValue);
        }
        if ($this->readonly) {
            $this->setAttr('readonly', 'readonly');
        }
        if ($this->disabled) {
            $this->setAttr('disabled', 'disabled');
        }
        $this->addClass('cres:element:control:AutoNumeric');
        $this->setAttr('cres-element', 'control:AutoNumeric');
        $this->setAttr('cres-config', \c::json($this->buildControlConfig()));
    }

    protected function buildControlConfig()
    {
        $config = [
            'applyJs' => 'autoNumeric',
        ];

        return $config;
    }
}
