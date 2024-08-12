<?php

namespace Cresenity\Laravel\CObservable\Listener\Handler;

use Cresenity\Laravel\CElement\Traits\Property\TitlePropertyTrait;
use Cresenity\Laravel\CObservable\Listener\Handler;

class ToastHandler extends Handler
{
    use TitlePropertyTrait;

    protected $toastType = 'success';

    protected $message = '';

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setType($type)
    {
        $this->toastType = $type;

        return $this;
    }

    public function js()
    {
        $optionsToast = [];

        $js = "toastr['" . $this->toastType . "']('" . $this->message . "', '" . $this->title . "', {
            positionClass: 'toast-top-right',
            closeButton: true,
            progressBar: true,
            preventDuplicates: false,
            newestOnTop: false,

        });";

        return $js;
    }
}
