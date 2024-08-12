<?php

namespace Cresenity\Laravel\CElement\Element\FormInput;

use Cresenity\Laravel\CElement\FormInput;

class CElement_FormInput_SelectModal extends CElement_FormInput
{
    use CElement_Trait_UseViewTrait;

    protected $fields;

    protected $format;

    protected $keyField;

    protected $searchField;

    protected $limit;

    protected $title;

    protected $itemName;

    protected $imgSrc;

    protected $minWidth;

    protected $minHeight;

    protected $buttonLabel;

    protected $placeholder;

    protected $formatSelection;

    protected $formatResult;

    protected $delay;

    public function __construct($id)
    {
        parent::__construct($id);

        $this->type = 'selectModal';
        $this->tag = 'div';
        $this->format = '';
        $this->fields = '';
        $this->keyField = '';
        $this->searchField = '';
        $this->limit = 10;
        $this->title = c::__('Please choose an Item');
        $this->itemName = '';
        $this->imgSrc = CApp_Base::noImageUrl();
        $this->minWidth = '100';
        $this->minHeight = '100';
        $this->buttonLabel = 'Select an Item';
        $this->placeholder = 'Search Item';
        $this->delay = '1000';
        $this->view = 'cresenity/element/form-input/select-modal';

        $this->onBeforeParse(function (CView_View $view) {
            $view->with('id', $this->id);
            $view->with('title', $this->title);
            $view->with('itemName', $this->itemName);
            $view->with('imgSrc', $this->imgSrc);
            $view->with('minWidth', $this->minWidth);
            $view->with('minHeight', $this->minHeight);
            $view->with('buttonLabel', $this->buttonLabel);
            $view->with('placeholder', $this->placeholder);
            $view->with('delay', $this->delay);
            $view->with('preTag', $this->pretag());
            $view->with('postTag', $this->posttag());
            $view->with('name', $this->name);
            $view->with('value', $this->value);
            $view->with('ajaxName', $this->createAjaxName());
            $view->with('ajaxUrl', $this->createAjaxUrl());
        });
    }

    public static function factory($id = null)
    {
        return new CElement_FormInput_SelectModal($id);
    }

    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function setKeyField($key)
    {
        $this->keyField = $key;

        return $this;
    }

    public function setSearchField(array $fields)
    {
        $this->searchField = $fields;

        return $this;
    }

    public function setLimit($total)
    {
        $this->limit = $total;

        return $this;
    }

    public function setTitle($title, $lang = true)
    {
        if ($lang) {
            $title = c::__($title);
        }
        $this->title = $title;

        return $this;
    }

    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function setImgSrc($imgsrc)
    {
        $this->imgSrc = $imgsrc;

        return $this;
    }

    public function setMinWidth($minWidth)
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    public function setMinHeight($minHeight)
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    public function setButtonLabel($label, $lang = true)
    {
        if ($lang) {
            $label = c::__($label);
        }
        $this->buttonLabel = $label;

        return $this;
    }

    public function setPlaceholder($placeholder, $lang = true)
    {
        if ($lang) {
            $placeholder = c::__($placeholder);
        }
        $this->placeholder = $placeholder;

        return $this;
    }

    public function setItemTemplateName($templateName)
    {
        $this->itemTemplateName = $templateName;

        return $this;
    }

    public function setItemTemplateVariables(array $vars)
    {
        $this->itemTemplateVariables = $vars;

        return $this;
    }

    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    public function createAjaxName()
    {
        $ajaxName = $this->name;
        $ajaxName = str_replace('[', '-', $this->name);
        $ajaxName = str_replace(']', '-', $ajaxName);

        return $ajaxName;
    }

    public function createAjaxUrl()
    {
        return CAjax::createMethod()
            ->setType('SelectModal')
            ->setData('format', $this->format)
            ->setData('fields', $this->fields)
            ->setData('keyField', $this->keyField)
            ->setData('searchField', $this->searchField)
            ->setData('limit', $this->limit)
            ->makeurl();
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
}
