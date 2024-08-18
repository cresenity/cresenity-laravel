<?php

namespace Cresenity\Laravel\CElement\FormInput;

use Cresenity\Laravel\CAjax;
use Cresenity\Laravel\CAjax\Engine\FileUpload;
use Cresenity\Laravel\CElement\FormInput;
use Cresenity\Laravel\CElement\Traits\UseViewTrait;
use Illuminate\View\View;

class FileAjax extends FormInput
{
    use UseViewTrait;

    protected $fileName;

    protected $acceptFile;

    protected $maxUploadSize;   // in MB

    protected $allowedExtension;

    protected $validationCallback;

    protected $disabledUpload;

    protected $tempStorage;

    protected $withInfo;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->type = 'file';
        $this->tag = 'div';
        $this->fileName = '';
        $this->withInfo = false;
        $this->acceptFile = '.doc,.docx,.xml,.pdf';
        $this->maxUploadSize = 0;
        $this->allowedExtension = [];
        $this->disabledUpload = false;
        $this->view = 'cresenity/element/form-input/file-ajax';
        $this->onBeforeParse(function (View $view) {
            $ajaxName = $this->name;
            $ajaxName = str_replace('[', '-', $ajaxName);
            $ajaxName = str_replace(']', '-', $ajaxName);

            $ajaxUrl = CAjax::createMethod()->setType(FileUpload::class)
                ->setData('inputName', $ajaxName)
                ->setData('allowedExtension', $this->allowedExtension)
                ->setData('validationCallback', $this->validationCallback)
                ->setData('withInfo', $this->withInfo)
                ->makeUrl();

            $view->with('id', $this->id);
            $view->with('fileName', $this->fileName);
            $view->with('acceptFile', $this->acceptFile);
            $view->with('maxUploadSize', $this->maxUploadSize);
            $view->with('disabledUpload', $this->disabledUpload);
            $view->with('preTag', $this->pretag());
            $view->with('postTag', $this->posttag());
            $view->with('name', $this->name);
            $view->with('value', $this->value);
            $view->with('ajaxName', $ajaxName);
            $view->with('ajaxUrl', $ajaxUrl);
        });
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function setAcceptFile($accept)
    {
        $this->acceptFile = $accept;

        return $this;
    }

    public function setMaxUploadSize($size)
    {
        $this->maxUploadSize = $size;

        return $this;
    }

    public function setWithInfo($withInfo = true)
    {
        $this->withInfo = $withInfo;

        return $this;
    }

    /**
     * @param int $ext
     *
     * @return $this
     */
    public function setAllowedExtension($ext)
    {
        $arr = $ext;
        if (!is_array($arr)) {
            $arr = [$ext];
        }
        $this->allowedExtension = $arr;

        return $this;
    }

    public function setValidationCallback($callback)
    {
        $this->validationCallback = c::toSerializableClosure($callback);

        return $this;
    }

    public function setDisabledUpload($bool)
    {
        $this->disabledUpload = $bool;

        return $this;
    }

    public function html($indent = 0)
    {
        $templateHtml = $this->getViewHtml();
        $html = $templateHtml;

        return $html;
    }

    public function js($indent = 0)
    {
        $templateJs = $this->getViewJs();
        $js = $templateJs;

        return $js;
    }

    public function setTempStorage($tempStorage)
    {
        $this->tempStorage = $tempStorage;

        return $this;
    }
}
