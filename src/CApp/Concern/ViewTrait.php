<?php

use Illuminate\Contracts\View\View;

trait CApp_Concern_ViewTrait {
    private $viewName = 'capp/page';

    private $viewLoginName = 'capp/login';

    /**
     * View.
     *
     * @var Illuminate\Contracts\View\View
     */
    private $view;

    /**
     * @return Illuminate\Contracts\View\View
     */
    public function getView() {
        if ($this->view == null) {
            $viewName = $this->viewName;

            $v = CView::factory($viewName);

            $this->view = $v;
        }

        return $this->view;
    }

    public function setView($view) {
        if (!($view instanceof View)) {
            $view = CView::factory($view);
        }
        $this->view = $view;
        $this->viewName = $view->getName();

        return $this;
    }
}
