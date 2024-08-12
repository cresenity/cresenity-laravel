<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CElement\ElementList\ActionList;
use Cresenity\Laravel\CElement\Traits\Property\TitlePropertyTrait;
use Cresenity\Laravel\CObservable\HandlerElement;
use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\CloseHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class DialogHandler extends Handler
{
    use AjaxHandlerTrait,
        TargetHandlerTrait,
        CloseHandlerTrait,
        TitlePropertyTrait;

    protected $content;

    protected $param;

    protected $actions;

    protected $param_inputs;

    protected $param_request;

    protected $isSidebar;

    protected $isFull;

    protected $modalClass;

    protected $backdrop;

    public function __construct($listener)
    {
        parent::__construct($listener);
        $this->name = 'Dialog';
        $this->method = 'get';
        $this->target = '';
        $this->content = HandlerElement::factory();
        $this->actions = ActionList::factory();
        $this->param_inputs = [];
        $this->param_request = [];
        $this->title = 'Detail';
        // $this->js_class = null;
        // $this->js_class_manual = null;
    }

    public function setSidebar($bool = true)
    {
        $this->isSidebar = $bool;

        return $this;
    }

    public function setFull($bool = true)
    {
        $this->isFull = $bool;

        return $this;
    }

    public function setModalClass($class)
    {
        $this->modalClass = $class;

        return $this;
    }

    public function setBackdrop($backdrop)
    {
        $this->backdrop = $backdrop;

        return $this;
    }

    public function addParamInput($inputs)
    {
        if (!is_array($inputs)) {
            $inputs = [$inputs];
        }
        foreach ($inputs as $inp) {
            $this->param_inputs[] = $inp;
        }

        return $this;
    }

    public function addParamRequest($param_request)
    {
        if (!is_array($param_request)) {
            $param_request = [$param_request];
        }
        foreach ($param_request as $req_k => $req_v) {
            $this->param_request[$req_k] = $req_v;
        }

        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function content()
    {
        return $this->content;
    }

    public function js()
    {
        $js = '';
        if (strlen($this->target) == 0) {
            $this->target = 'modal_opt_' . $this->event . '_' . $this->owner . '_dialog';
        }

        $dataAddition = '';

        foreach ($this->param_inputs as $inp) {
            if (strlen($dataAddition) > 0) {
                $dataAddition .= ',';
            }
            $dataAddition .= "'" . $inp . "':cresenity.value('#" . $inp . "')";
        }
        foreach ($this->param_request as $req_k => $req_v) {
            if (strlen($dataAddition) > 0) {
                $dataAddition .= ',';
            }
            $dataAddition .= "'" . $req_k . "':'" . $req_v . "'";
        }
        $dataAddition = '{' . $dataAddition . '}';

        $generatedUrl = $this->generatedUrl();

        $reloadOptions = '{';
        $reloadOptions .= "url:'" . $generatedUrl . "',";
        $reloadOptions .= "method:'" . $this->method . "',";
        $reloadOptions .= 'dataAddition:' . $dataAddition . ',';
        $reloadOptions .= '}';

        $backdropValue = "'static'";

        if (is_bool($this->backdrop)) {
            $backdropValue = $this->backdrop ? 'true' : 'false';
        }

        $jsOptions = '{';
        $jsOptions .= "selector:'#" . $this->target . "',";
        $jsOptions .= "title:'" . $this->title . "',";
        $jsOptions .= "modalClass:'" . $this->modalClass . "',";
        $jsOptions .= 'backdrop:' . $backdropValue . ',';
        $jsOptions .= 'reload:' . $reloadOptions . ',';
        if ($this->haveCloseListener()) {
            $jsOptions .= 'onClose:' . $this->getCloseListener()->js() . ',';
        }
        $jsOptions .= 'isSidebar:' . ($this->isSidebar ? 'true' : 'false') . ',';
        $jsOptions .= 'isFull:' . ($this->isFull ? 'true' : 'false') . ',';

        $jsOptions .= '}';

        $js_class = '';
        // $js_class = ccfg::get('js_class');
        // if (strlen($js_class) > 0) {
        //     $this->js_class = $js_class;
        // }
        // if ($this->js_class_manual != null) {
        //     $this->js_class = $this->js_class_manual;
        // }
        if (strlen($js_class) > 0 && $js_class != 'cresenity') {
            if ($this->content instanceof HandlerElement) {
                $content = $this->content->html();
            } else {
                $content = $this->content;
            }
            $content = addslashes($content);
            $content = str_replace("\r\n", '', $content);
            if (strlen(trim($content)) == 0) {
                $content = $this->generatedUrl();
            }
            $js .= '
                $.' . $js_class . ".show_dialog('" . $this->target . "','" . $this->title . "','" . $content . "');
            ";
        } else {
            $js .= 'cresenity.modal(' . $jsOptions . ');';
        }

        return $js;
    }
}
