<?php

class CImage_GoogleChart_VennDiagram extends CImage_GoogleChart_Chart {
    private $sizes;

    private $intersections;

    private $numData;

    public function __construct($width = 200, $height = 200) {
        $this->setProperty('cht', 'v');
        $this->setDimensions($width, $height);
        $this->sizes = [0, 0, 0];
        $this->intersections = [0, 0, 0, 0];
        $this->numData = 2;
    }

    public function setSizes($A = 0, $B = 0, $C = 0) {
        if ($C) {
            $this->numData = 3;
        }
        $this->sizes = [$A, $B, $C];
    }

    public function setIntersections($AB = 0, $AC = 0, $BC = 0, $ABC = 0) {
        $this->intersections = [$AB, $AC, $BC, $ABC];
    }

    public function setDataSetString() {
        $fullDataSet = array_merge($this->sizes, $this->intersections);
        $this->setProperty('chd', $this->getEncodingType() . ':' . $this->encodeData($fullDataSet, ',', $this->getEncodingType()));
    }

    public function getApplicableLabels($labels) {
        return array_splice($labels, 0, $this->numData);
    }
}
