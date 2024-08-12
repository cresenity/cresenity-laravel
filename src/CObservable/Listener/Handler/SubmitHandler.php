<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CObservable\Listener\Handler;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\AjaxHandlerTrait;
use Cresenity\Laravel\CObservable\Listener\Handler\Traits\TargetHandlerTrait;

class SubmitHandler extends Handler
{
    use TargetHandlerTrait,
        AjaxHandlerTrait;

    protected $formId;

    public function __construct($listener)
    {
        parent::__construct($listener);

        $this->name = 'Submit';
        $this->formId = '';
    }

    public function setForm($formId)
    {
        $this->formId = $formId;

        return $this;
    }

    public function js()
    {
        $js = '';
        if (strlen($this->formId) == 0) {
            $js .= "$('#" . $this->owner . "').closest('form').submit();";
        } else {
            $js .= "$('#" . $this->formId . "').submit();";
        }

        return $js;
    }
}
