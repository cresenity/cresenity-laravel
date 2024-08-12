<?php

defined('SYSPATH') or die('No direct access allowed.');

class CAjax_Engine_SelectSearch_Processor_DataProvider extends CAjax_Engine_SelectSearch_Processor {
    use CAjax_Engine_DataTable_Trait_ProcessorTrait;

    public function process() {
        $dataProvider = $this->dataProvider();
        /** @var CElement_Depends_DependsOn[] $dependsOn */
        $dependsOn = $this->dependsOn();

        /** @var CManager_Contract_DataProviderInterface $query */
        $dataProvider->searchOr($this->getSearchDataOr());
        $dataProvider->sort($this->getSortData());
        $page = $this->parameter->page();
        $prependData = [];
        $prependDataCount = count($this->prependData());
        if ($page == 1) {
            $prependData = $this->prependData();
        }
        $paginationResult = $dataProvider->paginate($this->parameter->pageSize(), ['*'], 'page', $page, function ($q) use ($dependsOn) {
            foreach ($dependsOn as $key => $dependOn) {
                $resolver = $dependOn->getResolver();

                $value = carr::get($this->input, 'dependsOn_' . $key);

                $this->engine->invokeCallback($resolver, [$q, $value]);
            }
        });

        $items = c::collect($prependData)->merge($paginationResult->items());

        $data = $items->map(function ($model) {
            $data = $model;
            if (is_string($data)) {
                $str = $data;
                $data = [
                    'id' => $str
                ];
            }
            if ($model instanceof CModel) {
                $data = $model->toArray();
                if ($this->keyField() && $model->{$this->keyField()}) {
                    $data['id'] = $model->{$this->keyField()};
                }
            } else {
                if ($this->keyField() && !isset($data['id'])) {
                    $data['id'] = carr::get($data, $this->keyField());
                }
            }
            $formatResult = $this->formatResult();
            if ($formatResult instanceof CFunction_SerializableClosure) {
                $formatResult = $formatResult->__invoke($model);
                if ($formatResult instanceof CRenderable) {
                    $data['cappFormatResult'] = $formatResult->html();
                    $data['cappFormatResultIsHtml'] = true;
                } else {
                    $data['cappFormatResult'] = $formatResult;
                    $data['cappFormatResultIsHtml'] = c::isHtml($formatResult);
                }
            }
            $formatSelection = $this->formatSelection();
            if ($formatSelection instanceof CFunction_SerializableClosure) {
                $formatSelection = $formatSelection->__invoke($model);
                if ($formatSelection instanceof CRenderable) {
                    $data['cappFormatSelection'] = $formatSelection->html();
                    $data['cappFormatSelectionIsHtml'] = true;
                } else {
                    $data['cappFormatSelection'] = $formatSelection;
                    $data['cappFormatSelectionIsHtml'] = c::isHtml($formatSelection);
                }
            }

            return $data;
        });
        $total = $paginationResult->total() + $prependDataCount;

        return c::response()->jsonp($this->callback(), [
            'data' => $data,
            'total' => $total,
        ]);
    }

    protected function getSearchDataOr() {
        $searchData = [];
        $searchTerm = $this->searchTerm();
        if (strlen($searchTerm) > 0) {
            foreach ($this->searchField() as $field) {
                if (strlen($field) > 0) {
                    $searchData[$field] = $this->searchTerm();
                }
            }
        }

        return $searchData;
    }

    protected function getSortData() {
        return [];
    }
}
