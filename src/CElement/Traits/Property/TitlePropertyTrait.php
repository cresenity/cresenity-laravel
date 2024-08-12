<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

trait TitlePropertyTrait
{
    protected $title;

    protected $rawTitle;

    /**
     * @param string     $title
     * @param bool|array $lang
     *
     * @return $this
     */
    public function setTitle($title, $lang = true)
    {
        $this->rawTitle = $title;
        if ($lang !== false) {
            $title = \c::__($title, is_array($lang) ? $lang : []);
        }
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->rawTitle;
    }

    public function getTranslationTitle()
    {
        return $this->title;
    }

    public function haveTitle()
    {
        return strlen($this->title) > 0;
    }

    /**
     * Call getTitle if parameter title is not passed
     * Call setTitle if parameter title is passed.
     *
     * @param string     $title
     * @param bool|array $lang
     *
     * @return mixed
     */
    public function title($title = null, $lang = true)
    {
        if ($title !== null) {
            return $this->setTitle($title, $lang);
        }

        return $this->getTitle();
    }
}
