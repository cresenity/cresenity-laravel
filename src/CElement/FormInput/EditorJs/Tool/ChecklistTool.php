<?php

namespace Cresenity\Laravel\CElement\Element\FormInput\EditorJs\Tool;

use Cresenity\Laravel\CElement\Element\FormInput\EditorJs\DefaultConfig;
use Cresenity\Laravel\CElement\Element\FormInput\EditorJs\ToolAbstract;
use Cresenity\Laravel\CElement\Traits\Property\ShortcutPropertyTrait;

/**
 * @see CElement_FormInput_EditorJs
 */
class ChecklistTool extends ToolAbstract
{
    use ShortcutPropertyTrait;

    protected $inlineToolbar;

    public function __construct()
    {
        $this->enabled = DefaultConfig::get('toolSettings.checklist.enabled');
        $this->shortcut = DefaultConfig::get('toolSettings.checklist.shortcut');
        $this->inlineToolbar = DefaultConfig::get('toolSettings.checklist.inlineToolbar');
    }

    public function getConfig()
    {
        return [
            'enabled' => (bool) $this->enabled,
            'inlineToolbar' => $this->inlineToolbar,
            'shortcut' => (string) $this->shortcut,
        ];
    }
}
