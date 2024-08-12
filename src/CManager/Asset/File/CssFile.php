<?php

class CManager_Asset_File_CssFile extends CManager_Asset_FileAbstract {
    protected $media;

    protected $attributes;

    public function __construct(array $options) {
        parent::__construct($options);
        $this->type = 'css';

        $this->media = carr::get($options, 'media');
        $this->attributes = carr::get($options, 'attributes', []);
    }

    public function getUrl($withHttp = false) {
        if ($this->isRemote) {
            return $this->script;
        }
        $file = $this->getPath();
        $path = $file;
        $path = carr::first(explode('?', $file));

        $docroot = str_replace(DS, '/', CF::publicPath() ? CF::publicPath() . '/' : DOCROOT);
        $file = str_replace(DS, '/', $file);
        $base_url = curl::base();
        if ($withHttp) {
            $base_url = curl::base(false, 'http');
        }

        $file = str_replace($docroot, $base_url, $file);

        $file = str_replace(str_replace(DS, '/', DOCROOT), $base_url, $file);
        if (CF::config('assets.css.versioning')) {
            $separator = parse_url($file, PHP_URL_QUERY) ? '&' : '?';
            $interval = CF::config('assets.css.interval', 0);
            $version = CManager_Asset_Helper::getFileVersion($path, $interval);
            $file .= $separator . 'v=' . $version;
        }

        return $file;
    }

    protected function fullpath($file) {
        $dirs = $this->getMediaPaths();
        foreach ($dirs as $dir) {
            $path = $dir . 'css' . DS . $file;

            if (file_exists($path)) {
                return $path;
            }
        }

        $path = DOCROOT . 'media' . DS . 'css' . DS;

        return $path . $file;
    }

    public function render($withHttp = false) {
        $url = $this->getUrl($withHttp);

        $attributes = $this->attributes;
        if ($this->media) {
            $attributes['media'] = $this->media;
        }
        if (!isset($attributes['rel'])) {
            $attributes['rel'] = 'stylesheet';
        }
        $script = '<link href="' . $url . '"' . CBase_HtmlBuilder::attributes($attributes) . ' />';

        return $script;
    }
}
