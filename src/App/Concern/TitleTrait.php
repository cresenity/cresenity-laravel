<?php
namespace Cresenity\Laravel\App\Concern;

trait TitleTrait
{
    protected $title;

    protected $rawTitle;

    private $showTitle = true;

    public function setTitle($title, $lang = true)
    {
        /** @var CApp $this */
        $this->rawTitle = $title;
        if ($lang) {
            $title = c::__($title);
        }
        $this->title = $title;

        $this->seo()->setTitle($title);

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
     * @param string $title
     * @param bool   $lang
     *
     * @return mixed
     */
    public function title($title = null, $lang = true)
    {
        if ($title != null) {
            return $this->setTitle($title, $lang);
        }

        return $this->getTitle();
    }

    public function showTitle($bool)
    {
        $this->showTitle = $bool;

        return $this;
    }
}
