<?php
namespace Cresenity\Laravel\CElement\Component;

use Cresenity\Laravel\CElement\Component;
use Cresenity\Laravel\CElement\Component\Blockly\Helper as Helper;
use Cresenity\Laravel\CManager;

class Blockly extends Component
{
    protected $mediaDirectory;

    protected $toolbox;

    protected $toolbar;

    protected $blocklyWrapper;

    protected $saveAction;

    protected $variables;

    protected $isFunctionWithReturn = false;

    protected $functionName = '';

    protected $functionArgs = [];

    protected $saveUrl = '';

    public function __construct($id = '', $tag = 'div')
    {
        parent::__construct($id, $tag);
        if (!CManager::isRegisteredModule('blockly')) {
            CManager::registerModule('blockly');
        }
        $this->tag = 'div';
        $this->toolbox = new CElement_Component_Blockly_Toolbox();
        $this->toolbar = new CElement_List_ActionList();
        $this->blocklyWrapper = new CElement_Element_Div();

        $this->addClass('capp-blockly');
        $this->blocklyWrapper->customCss('height', '600px');
        $this->add($this->toolbar);
        $this->blocklyWrapper->add($this->toolbox);
        $this->add($this->blocklyWrapper);
        $this->saveAction = $this->toolbar->addAction()->setLabel('Save');
        $this->variables = [];
    }

    public function addVariable($variable)
    {
        $this->variables[] = $variable;
        return $this;
    }

    public function setFunctionWithReturn($funcName, $arguments = [])
    {
        $this->isFunctionWithReturn = true;
        $this->functionName = $funcName;
        $this->functionArgs = $arguments;
    }

    public function setSaveUrl($url)
    {
        $this->saveUrl = $url;
        return $this;
    }

    public function build()
    {
    }

    public function js($indent = 0)
    {
        $toolboxId = $jsOptions = [];
        $jsOptions['blocklyElementId'] = $this->blocklyWrapper->id();
        $jsOptions['toolboxElementId'] = $this->toolbox->id();
        $jsOptions['saveElementId'] = $this->saveAction->id();
        $jsOptions['mediaFolder'] = '/modules/cresenity/media/js/blockly/media/';
        $jsOptions['variables'] = $this->variables;
        $jsOptions['saveUrl'] = $this->saveUrl;
        if ($this->isFunctionWithReturn) {
            $jsOptions['defaultXml'] = Helper::buildDefaultXmlForFunction($this->functionName, $this->functionArgs);
        }
        return '
            new CBlockly(' . json_encode($jsOptions) . ');
        ';
    }
}
