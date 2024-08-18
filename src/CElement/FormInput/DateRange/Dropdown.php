<?php

namespace Cresenity\Laravel\CElement\FormInput\DateRange;

use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\MomentJsTrait;
use Cresenity\Laravel\CManager;
use Cresenity\Laravel\CPeriod;

class Dropdown extends FormInput
{
    use MomentJsTrait;

    protected $dateFormat;

    protected $momentFormat;

    protected $dateStart;

    protected $dateEnd;

    public function __construct($id)
    {
        parent::__construct($id);

        CManager::instance()->registerModule('bootstrap-daterangepicker');

        $this->type = 'text';
        $dateFormat = \c::formatter()->getDateFormat();
        if ($dateFormat == null) {
            $dateFormat = 'Y-m-d';
        }
        $this->dateFormat = $dateFormat;
        $this->momentFormat = $this->convertPHPToMomentFormat($dateFormat);
    }

    public function setValue($value)
    {
        if ($value instanceof CPeriod) {
            $this->setValueStart($value->startDate);
            $this->setValueEnd($value->endDate);
        } else {
            $this->setValueStart($value);
            $this->setValueEnd($value);
        }

        return $this;
    }

    public function setValueStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function setValueEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function build()
    {
        $this->addClass('form-control');
    }

    public function js($indent = 0)
    {
        $js = '';
        $js .= "
            $('#" . $this->id . "').daterangepicker({
                opens: 'left',
                locale: {
                    format: '" . $this->momentFormat . "'
                },

            });
            ";

        return $js;
    }
}
