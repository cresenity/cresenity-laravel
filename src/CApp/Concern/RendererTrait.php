<?php

trait CApp_Concern_RendererTrait {
    protected $rendered = false;

    protected $viewData = null;

    public function getViewData() {
        if ($this->viewData == null) {
            $viewData = [];

            $this->viewData = $viewData;
        }

        return $this->viewData;
    }

    public function rendered() {
        return $this->rendered;
    }

    /**
     * Render the html of this.
     *
     * @throws CException
     * @throws CApp_Exception
     *
     * @return string
     */
    public function render() {
        //$this->registerCoreModules();

        // if (c::request()->ajax()) {
        //     return $this->json();
        // }

        // CView::factory()->share(
        //     'errors',
        //     CSession::store()->get('errors') ?: new CBase_ViewErrorBag()
        // );

        $viewData = $this->getViewData();
        $v = $this->getView();
        /** @var \Illuminate\View\View $v */
        $v->with($viewData);

        return $v->render();
    }
}
