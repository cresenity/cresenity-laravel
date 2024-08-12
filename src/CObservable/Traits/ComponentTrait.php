<?php

namespace Cresenity\Laravel\CObservable\Traits;

trait ComponentTrait
{
    /**
     * @param null|string $id
     *
     * @return CElement_Component_DataTable
     */
    public function addTable($id = null)
    {
        $table = new CElement_Component_DataTable($id);
        $this->wrapper->add($table);

        return $table;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_ListGroup
     */
    public function addListGroup($id = null)
    {
        $listGroup = new CElement_Component_ListGroup($id);
        $this->wrapper->add($listGroup);

        return $listGroup;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Nestable
     */
    public function addNestable($id = null)
    {
        $nestable = new CElement_Component_Nestable($id);
        $this->wrapper->add($nestable);

        return $nestable;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Terminal
     */
    public function addTerminal($id = null)
    {
        $terminal = new CElement_Component_Terminal($id);
        $this->wrapper->add($terminal);

        return $terminal;
    }

    /**
     * @param string      $type
     * @param null|string $id
     *
     * @return CElement_Component_Chart
     */
    public function addChart($type = 'Chart', $id = '')
    {
        $chart = CElement_Component_Chart::factory($type, $id);
        $this->wrapper->add($chart);

        return $chart;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_ElFinder
     */
    public function addElFinder($id = null)
    {
        $elFinder = new CElement_Component_ElFinder($id);
        $this->wrapper->add($elFinder);

        return $elFinder;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_FileManager
     */
    public function addFileManager($id = null)
    {
        $fileManager = new CElement_Component_FileManager($id);
        $this->add($fileManager);

        return $fileManager;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Widget
     */
    public function addWidget($id = null)
    {
        $widget = new CElement_Component_Widget($id);
        $this->wrapper->add($widget);

        return $widget;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Form
     */
    public function addForm($id = null)
    {
        $form = new CElement_Component_Form($id);
        $this->wrapper->add($form);

        return $form;
    }

    /**
     * Add Action Element.
     *
     * @param string $id optional
     *
     * @return CElement_Component_Action
     */
    public function addAction($id = null)
    {
        $act = new CElement_Component_Action($id);
        $this->wrapper->add($act);

        return $act;
    }

    /**
     * @param string $id
     *
     * @return CElement_Component_Alert
     */
    public function addAlert($id = null)
    {
        $element = new CElement_Component_Alert($id);
        $this->wrapper->add($element);

        return $element;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Kanban
     */
    public function addKanban($id = null)
    {
        $kanban = new CElement_Component_Kanban($id);
        $this->wrapper->add($kanban);

        return $kanban;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_PdfViewer
     */
    public function addPdfViewer($id = null)
    {
        $pdfViewer = CElement_Factory::createComponent('PdfViewer', $id);
        $this->wrapper->add($pdfViewer);

        return $pdfViewer;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_TreeView
     */
    public function addTreeView($id = null)
    {
        $treeView = CElement_Factory::createComponent('TreeView', $id);
        $this->add($treeView);

        return $treeView;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_PrismCode
     */
    public function addPrismCode($id = null)
    {
        $prismCode = new CElement_Component_PrismCode($id);
        $this->wrapper->add($prismCode);

        return $prismCode;
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Blockly
     */
    public function addBlockly($id = null)
    {
        return c::tap(new CElement_Component_Blockly($id), function (CElement_Component_Blockly $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Shimmer
     */
    public function addShimmer($id = null)
    {
        return c::tap(new CElement_Component_Shimmer($id), function (CElement_Component_Shimmer $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_ShowMore
     */
    public function addShowMore($id = null)
    {
        return c::tap(new CElement_Component_ShowMore($id), function (CElement_Component_ShowMore $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_CountDownTimer
     */
    public function addCountDownTimer($id = null)
    {
        return c::tap(new CElement_Component_CountDownTimer($id), function (CElement_Component_CountDownTimer $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Repeater
     */
    public function addRepeater($id = null)
    {
        return c::tap(new CElement_Component_Repeater($id), function (CElement_Component_Repeater $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Gallery
     */
    public function addGallery($id = null)
    {
        return c::tap(new CElement_Component_Gallery($id), function (CElement_Component_Gallery $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_ProgressBar
     */
    public function addProgressBar($id = null)
    {
        return \c::tap(new CElement_Component_ProgressBar($id), function (CElement_Component_ProgressBar $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Image
     */
    public function addImage($id = null)
    {
        return \c::tap(new CElement_Component_Image($id), function (CElement_Component_Image $el) {
            $this->wrapper->add($el);
        });
    }

    /**
     * @param null|string $id
     *
     * @return CElement_Component_Metric_ValueMetric
     */
    public function addValueMetric($id = null)
    {
        return \c::tap(new CElement_Component_Metric_ValueMetric($id), function (CElement_Component_Metric_ValueMetric $el) {
            $this->wrapper->add($el);
        });
    }
}
