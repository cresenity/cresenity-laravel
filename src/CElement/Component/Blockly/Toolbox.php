<?php
namespace Cresenity\Laravel\CElement\Component\Blockly;

use Cresenity\Laravel\CElement\Element;

class Toolbox extends Element
{
    protected $categories = [];

    public function __construct($id = '', $tag = 'div')
    {
        parent::__construct($id, $tag);
        $this->tag = 'xml';
        $this->categories = [];
    }

    public function build()
    {
        $this->categories = ToolboxHelper::getAllCategoryData();
    }

    public function html($indent = 0)
    {
        $this->buildOnce();
        $xmlOpen = '<xml id="' . $this->id . '" style="display: none">';
        $xmlClose = '</xml>';

        $categoryXml = \c::collect($this->categories)->reduce(function ($output, $blockArray, $name) {
            return $output . CategoryHelper::renderCategory($name, $blockArray);
        }, '');

        $sepXml = '<sep></sep>';

        $customCategoriesXml = $sepXml . '

            <category name="' . ucfirst(CategoryHelper::CATEGORY_VARIABLES) . '" colour="' . CategoryHelper::$categoryHue[CategoryHelper::CATEGORY_VARIABLES] . '" custom="VARIABLE"></category>
            <category name="' . ucfirst(CategoryHelper::CATEGORY_PROCEDURES) . '" colour="' . CategoryHelper::$categoryHue[CategoryHelper::CATEGORY_PROCEDURES] . '" custom="PROCEDURE"></category>';

        return $xmlOpen . $categoryXml . $customCategoriesXml . $xmlClose;
    }
}
