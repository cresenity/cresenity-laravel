<?php

namespace Cresenity\Laravel\CElement;

use Cresenity\Laravel\CElement;
use Cresenity\Laravel\CElement\Traits\UseViewTrait;
use Illuminate\View\View as IlluminateView;

class View extends CElement
{
    use UseViewTrait;

    protected $viewElement;

    public function __construct($id, $view = null, $data = [])
    {
        parent::__construct($id);
        if ($view != null) {
            $this->setView($view, $data);
        }
        $this->viewElement = [];
        $this->htmlJs = null;
        $this->onBeforeParse(function (IlluminateView $view) {
            $view->with('__CAppElementView', $this);
        });
    }

    public function html($indent = 0)
    {
        return $this->getViewHtml($indent);
    }

    public function js($indent = 0)
    {
        return $this->getViewJs($indent);
    }

    /**
     * Get Element By Key.
     *
     * @param string $key
     *
     * @return \Cresenity\Laravel\CElement\PseudoElement
     */
    public function viewElement($key)
    {
        if (!isset($this->viewElement[$key])) {
            $this->viewElement[$key] = new PseudoElement();
        }

        return $this->viewElement[$key];
    }
}
