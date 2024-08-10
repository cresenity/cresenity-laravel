<?php

defined('SYSPATH') or die('No direct access allowed.');

/**
 * @author Hery Kurniawan
 * @license Ittron Global Teknologi <ittron.co.id>
 *
 * @since Mar 27, 2019, 12:44:24 AM
 */
class CElement_Component_ElFinder extends CElement_Component {
    private $connectorUrl = false;

    public function __construct($id = '') {
        parent::__construct($id);

        $this->tag = 'div';
    }

    public static function factory($id = '') {
        return new CElement_Component_ElFinder($id);
    }

    public function build() {
        CManager::instance()->asset()->module()->registerRunTimeModule('jquery.ui');
        CManager::instance()->asset()->module()->registerRunTimeModule('elfinder');
    }

    public function setConnectorUrl($connectorUrl) {
        $this->connectorUrl = $connectorUrl;
    }

    public function js($indent = 0) {
        $options = [];
        $contextMenu = [
            'cwd' => ['reload', 'upload', 'sort'],
            'files' => ['download', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', '|', 'info'],
        ];
        $options['requesttype'] = 'post';
        $options['url'] = $this->connectorUrl;
        $options['ui'] = ['stat'];
        $options['contextmenu'] = $contextMenu;

        $js = "
            jQuery('#" . $this->id() . "').elfinder(" . json_encode($options) . ');
        ';

        return $js;
    }
}
