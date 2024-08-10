<?php
namespace Cresenity\Laravel\CApp;

use Cresenity\Laravel\CApp\SEO\JsonLd;
use Cresenity\Laravel\CApp\SEO\MetaTags;
use Cresenity\Laravel\CApp\SEO\OpenGraph;
use Cresenity\Laravel\CApp\SEO\Twitter;
use Illuminate\Support\Arr;

class SEO
{
    private static $instance = null;

    private function __construct()
    {
        //do nothing
    }

    /**
     * @return \Cresenity\Laravel\CApp\SEO
     */
    public static function instance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return \Cresenity\Laravel\CApp\SEO\MetaTags
     */
    public function metatags()
    {
        return MetaTags::instance();
    }

    /**
     * @return \Cresenity\Laravel\CApp\SEO\OpenGraph
     */
    public function opengraph()
    {
        return OpenGraph::instance();
    }

    /**
     * @return \Cresenity\Laravel\CApp\SEO\Twitter
     */
    public function twitter()
    {
        return Twitter::instance();
    }

    /**
     * @return \Cresenity\Laravel\CApp\SEO\JsonLd
     */
    public function jsonLd()
    {
        return JsonLd::instance();
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title, $appendDefault = true)
    {
        $this->metatags()->setTitle($title, $appendDefault);
        $this->opengraph()->setTitle($title);
        $this->twitter()->setTitle($title);
        $this->jsonLd()->setTitle($title);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        $this->metatags()->setDescription($description);
        $this->opengraph()->setDescription($description);
        $this->twitter()->setDescription($description);
        $this->jsonLd()->setDescription($description);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCanonical($url)
    {
        $this->metatags()->setCanonical($url);

        return $this;
    }

    public function setImages($urls)
    {
        $this->opengraph()->setImages(Arr::wrap($urls));
        $this->twitter()->setImage($urls);
        $this->jsonLd()->setImages($urls);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addImages($urls)
    {
        if (is_array($urls)) {
            $this->opengraph()->addImages($urls);
        } else {
            $this->opengraph()->addImage($urls);
        }

        $this->twitter()->setImage($urls);

        $this->jsonLd()->addImage($urls);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTitle($session = false)
    {
        if ($session) {
            return $this->metatags()->getTitleSession();
        }

        return $this->metatags()->getTitle();
    }

    /**
     * @inheritdoc
     */
    public function generate($minify = false)
    {
        $html = $this->metatags()->generate();
        $html .= PHP_EOL;
        $html .= $this->opengraph()->generate();
        $html .= PHP_EOL;
        $html .= $this->twitter()->generate();
        $html .= PHP_EOL;
        $html .= $this->jsonLd()->generate();

        return ($minify) ? str_replace(PHP_EOL, '', $html) : $html;
    }
}
