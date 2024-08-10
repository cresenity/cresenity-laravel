<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * @author Hery Kurniawan
 * @license Ittron Global Teknologi <ittron.co.id>
 *
 * @see https://github.com/nehakadam/DateTimePicker
 */
class CElement_FormInput_DateTime_ModalDateTime extends CElement_FormInput_DateTime {
    /**
     * @var string
     */
    protected $parentElementSelector;

    public function __construct($id) {
        parent::__construct($id);
        c::manager()->registerModule('datetimepicker');

        $this->dateTimeFormat = 'YYYY-MM-DD';

        $dateTimeFormat = c::formatter()->getDatetimeFormat();
        if ($dateTimeFormat != null) {
            $dateTimeFormat = str_replace('Y', 'YYYY', $dateTimeFormat);
            $dateTimeFormat = str_replace('m', 'MM', $dateTimeFormat);
            $dateTimeFormat = str_replace('d', 'dd', $dateTimeFormat);
            $dateTimeFormat = str_replace('H', 'HH', $dateTimeFormat);
            $dateTimeFormat = str_replace('i', 'mm', $dateTimeFormat);
            $dateTimeFormat = str_replace('s', 'ss', $dateTimeFormat);
            $this->dateTimeFormat = $dateTimeFormat;
        }
    }

    protected function build() {
        $this->setReadonly();
        parent::build();
        $this->setAttr('data-field', 'datetime');
        $this->addClass('form-control');
        $this->addClass('cres-control-modal-datetime');
        $this->after()->addDiv($this->id . '-dtbox');
    }

    public function setParentElementSelector($selector) {
        if ($selector instanceof CRenderable) {
            $selector = '#' . $selector->id();
        }
        $this->parentElementSelector = $selector;
    }

    protected function getTranslation($key) {
        return c::__('element/date.datepicker.' . $key);
    }

    private function getShortDayNames() {
        return "[
            '" . $this->getTranslation('daysShort.Sun') . "',
            '" . $this->getTranslation('daysShort.Mon') . "',
            '" . $this->getTranslation('daysShort.Tue') . "',
            '" . $this->getTranslation('daysShort.Wed') . "',
            '" . $this->getTranslation('daysShort.Thu') . "',
            '" . $this->getTranslation('daysShort.Fri') . "',
            '" . $this->getTranslation('daysShort.Sat') . "'
        ]";
    }

    private function getFullDayNames() {
        return "[
            '" . $this->getTranslation('days.Sunday') . "',
            '" . $this->getTranslation('days.Monday') . "',
            '" . $this->getTranslation('days.Tuesday') . "',
            '" . $this->getTranslation('days.Wednesday') . "',
            '" . $this->getTranslation('days.Thursday') . "',
            '" . $this->getTranslation('days.Friday') . "',
            '" . $this->getTranslation('days.Saturday') . "'
        ]";
    }

    private function getShortMonthNames() {
        return "[
            '" . $this->getTranslation('monthsShort.Jan') . "',
            '" . $this->getTranslation('monthsShort.Feb') . "',
            '" . $this->getTranslation('monthsShort.Mar') . "',
            '" . $this->getTranslation('monthsShort.Apr') . "',
            '" . $this->getTranslation('monthsShort.May') . "',
            '" . $this->getTranslation('monthsShort.Jun') . "',
            '" . $this->getTranslation('monthsShort.Jul') . "',
            '" . $this->getTranslation('monthsShort.Aug') . "',
            '" . $this->getTranslation('monthsShort.Sep') . "',
            '" . $this->getTranslation('monthsShort.Oct') . "',
            '" . $this->getTranslation('monthsShort.Nov') . "',
            '" . $this->getTranslation('monthsShort.Dec') . "',
        ]";
    }

    private function getFullMonthNames() {
        return "[
            '" . $this->getTranslation('months.January') . "',
            '" . $this->getTranslation('months.February') . "',
            '" . $this->getTranslation('months.March') . "',
            '" . $this->getTranslation('months.April') . "',
            '" . $this->getTranslation('months.May') . "',
            '" . $this->getTranslation('months.June') . "',
            '" . $this->getTranslation('months.July') . "',
            '" . $this->getTranslation('months.August') . "',
            '" . $this->getTranslation('months.September') . "',
            '" . $this->getTranslation('months.October') . "',
            '" . $this->getTranslation('months.November') . "',
            '" . $this->getTranslation('months.December') . "'
        ]";
    }

    public function js($indent = 0) {
        $dateTimeFormat = $this->dateTimeFormat;
        $options = '{';
        $options .= "dateTimeFormat:'" . $dateTimeFormat . "',";
        $options .= 'shortDayNames:' . $this->getShortDayNames() . ',';
        $options .= 'fullDayNames:' . $this->getFullDayNames() . ',';
        $options .= 'shortMonthNames:' . $this->getShortMonthNames() . ',';
        $options .= 'fullMonthNames:' . $this->getFullMonthNames() . ',';
        $options .= "clearButtonContent:'" . $this->getTranslation('clear') . "',";
        if ($this->parentElementSelector) {
            $options .= "parentElement: '" . $this->parentElementSelector . "',";
        }

        $options .= 'isPopup:true,';
        $options .= '}';
        $js = "$('#" . $this->id . '-dtbox' . "').DateTimePicker(" . $options . ')';

        return $js;
    }
}
