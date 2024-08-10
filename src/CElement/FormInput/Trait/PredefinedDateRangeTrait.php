<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * @author Hery Kurniawan
 * @license Ittron Global Teknologi <ittron.co.id>
 *
 * @since Jun 13, 2019, 7:38:34 PM
 */
use Carbon\Carbon;

trait CElement_FormInput_Trait_PredefinedDateRangeTrait {
    protected $predefinedRanges;

    public function resetRange() {
        $this->predefinedRanges = [];

        return $this;
    }

    public function addRange($label, $dateStart, $dateEnd) {
        $this->predefinedRanges[] = [
            'label' => $label,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
        ];

        return $this;
    }

    public function addRangeLifeTime($label = 'element/date.daterange:lifetime', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }

        $dateStart = Carbon::createFromTimestamp(0);
        $dateEnd = Carbon::now();
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRangeToday($label = 'element/date.daterange:today', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }

        $dateStart = Carbon::now();
        $dateEnd = Carbon::now();
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRangeYesterday($label = 'element/date.daterange:yesterday', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }
        $dateStart = Carbon::yesterday()->hour(0)->minute(0)->second(0);
        $dateEnd = Carbon::yesterday()->hour(23)->minute(59)->second(59);
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRange7Days($label = 'element/date.daterange:lastNDays', $lang = true) {
        return $this->addRangeNDays(7, $label, $lang);
    }

    public function addRange14Days($label = 'element/date.daterange:lastNDays', $lang = true) {
        return $this->addRangeNDays(14, $label, $lang);
    }

    public function addRange30Days($label = 'element/date.daterange:lastNDays', $lang = true) {
        return $this->addRangeNDays(30, $label, $lang);
    }

    public function addRangeNDays($n, $label = 'element/date.daterange:lastNDays', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label, ['n' => $n]);
        }
        $dateStart = Carbon::now()->subDays($n - 1);
        $dateEnd = Carbon::now();
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRangeThisWeek($label = 'element/date.daterange:thisWeek', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }
        $dateStart = Carbon::now()->modify('this week');
        $dateEnd = Carbon::now()->modify('this week +6 days');
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRangeLastWeek($label = 'element/date.daterange:lastWeek', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }
        $dateStart = Carbon::now()->modify('last week');
        $dateEnd = Carbon::now()->modify('last week +6 days');
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRangeThisMonth($label = 'element/date.daterange:thisMonth', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }
        $dateStart = Carbon::now()->modify('first day of this month');
        $dateEnd = Carbon::now()->modify('last day of this month');
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addRangeLastMonth($label = 'element/date.daterange:lastMonth', $lang = true) {
        $labelTranslated = $label;
        if ($lang) {
            $labelTranslated = c::__($label);
        }
        $dateStart = Carbon::now()->modify('first day of last month');
        $dateEnd = Carbon::now()->modify('last day of last month');
        $this->addRange($labelTranslated, $dateStart, $dateEnd);

        return $this;
    }

    public function addDefaultRange() {
        $this->resetRange();
        $this->addRangeLifeTime();
        $this->addRangeToday();
        $this->addRangeYesterday();
        $this->addRange7Days();
        $this->addRange14Days();
        $this->addRange30Days();
        $this->addRangeThisWeek();
        $this->addRangeLastWeek();
        $this->addRangeThisMonth();
        $this->addRangeLastMonth();

        return $this;
    }
}
