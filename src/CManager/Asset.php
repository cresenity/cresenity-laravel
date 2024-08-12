<?php

namespace Cresenity\Laravel\CManager;

class Asset
{
    /**
     * POS CONST.
     */
    const POS_HEAD = 'head';

    const POS_BEGIN = 'begin';

    const POS_END = 'end';

    const POS_READY = 'ready';

    const POS_LOAD = 'load';

    /**
     * TYPE CONST.
     */
    const TYPE_JS_FILE = 'js_file';

    const TYPE_JS = 'js';

    const TYPE_CSS_FILE = 'css_file';

    const TYPE_CSS = 'css';

    const TYPE_META = 'meta';

    const TYPE_LINK = 'link';

    const TYPE_PLAIN = 'plain';

    /**
     * Array of all type script.
     *
     * @var array
     */
    public static $allType = [
        self::TYPE_JS_FILE,
        self::TYPE_JS,
        self::TYPE_CSS_FILE,
        self::TYPE_CSS,
        self::TYPE_META,
        self::TYPE_JS,
        self::TYPE_LINK,
        self::TYPE_PLAIN,
    ];

    /**
     * @var CManager_Asset_Container_Theme
     */
    protected $themeContainer;

    /**
     * @var CManager_Asset_Container_RunTime
     */
    protected $runTimeContainer;

    /**
     * @var CManager_Asset_Module
     */
    protected $module;

    public function __construct()
    {
        $this->runTimeContainer = new CManager_Asset_Container_RunTime();
        $this->themeContainer = new CManager_Asset_Container_Theme();
        $this->module = new CManager_Asset_Module();
    }

    public function reset()
    {
        $this->runTimeContainer->reset();
        $this->themeContainer->reset();
        $this->module->reset();
    }

    public static function allAvailablePos()
    {
        return [self::POS_HEAD, self::POS_BEGIN, self::POS_END, self::POS_LOAD, self::POS_READY];
    }

    public static function allAvailableType()
    {
        return [self::TYPE_JS_FILE, self::TYPE_JS, self::TYPE_CSS_FILE, self::TYPE_CSS, self::TYPE_META, self::TYPE_LINK];
    }

    /**
     * @return CManager_Asset_Container_RunTime
     */
    public function runTime()
    {
        return $this->runTimeContainer;
    }

    /**
     * @return CManager_Asset_Container_Theme
     */
    public function theme()
    {
        return $this->themeContainer;
    }

    /**
     * @return CManager_Asset_Module
     */
    public function &module()
    {
        return $this->module;
    }

    public function getAllCssFileUrl()
    {
        $themeCss = $this->themeContainer->getAllCssFileUrl();
        $runTimeCss = $this->runTimeContainer->getAllCssFileUrl();
        $moduleThemeCss = $this->module->getThemeContainer()->getAllCssFileUrl();
        $moduleRunTimeCss = $this->module->getRunTimeContainer()->getAllCssFileUrl();

        return array_merge($moduleThemeCss, $themeCss, $moduleRunTimeCss, $runTimeCss);
    }

    public function getAllJsFileUrl()
    {
        $moduleThemeJs = $this->module->getThemeContainer()->getAllJsFileUrl();
        $themeJs = $this->themeContainer->getAllJsFileUrl();
        $moduleRunTimeJs = $this->module->getRunTimeContainer()->getAllJsFileUrl();
        $runTimeJs = $this->runTimeContainer->getAllJsFileUrl();

        $allJs = array_merge($moduleThemeJs, $themeJs, $moduleRunTimeJs, $runTimeJs);

        return $allJs;
    }

    public function varJs()
    {
        $varJs = 'window.capp = ' . json_encode(c::app()->variables()) . ';';

        return $varJs;
    }

    public function wrapJs($js, $documentReady = false)
    {
        $js_before = '';

        if ($documentReady) {
            $js = 'jQuery(document).ready(function(){' . $js . '});';
        }
        // $js .= "
        //     if (typeof cappStartedEventInitilized === 'undefined') {
        //         cappStartedEventInitilized=false;
        //      }
        //     if(!cappStartedEventInitilized) {
        //         var evt = document.createEvent('Events');
        //         evt.initEvent('capp-started', false, true, window, 0);
        //         cappStartedEventInitilized=true;
        //         document.dispatchEvent(evt);
        //     }

        // ";

        $js .= CJavascript::compile();
        $bar = CDebug::bar();
        if ($bar->isEnabled()) {
            $js .= $bar->getJavascriptReplaceCode();
        }

        return $js_before . $js . PHP_EOL . ';' . PHP_EOL;
    }

    public function renderJsRequire($js, $require = 'cresenity.cf.requireJs')
    {
        //return CClientModules::instance()->require_js($js);
        $app = CApp::instance();

        $moduleThemejsFiles = $this->module->getThemeContainer()->jsFiles();
        $themejsFiles = $this->themeContainer->jsFiles();
        $moduleRunTimejsFiles = $this->module->getRunTimeContainer()->jsFiles();
        $runTimejsFiles = $this->runTimeContainer->jsFiles();

        $jsFiles = array_merge($moduleThemejsFiles, $themejsFiles, $moduleRunTimejsFiles, $runTimejsFiles);

        $jsOpen = '';
        $jsClose = '';
        $jsBefore = '';
        $i = 0;
        $manager = CManager::instance();

        if ($manager->getUseRequireJs()) {
            foreach ($jsFiles as $f) {
                $urlJsFile = CManager_Asset_Helper::urlJsFile($f);

                $jsOpen .= str_repeat("\t", $i) . $require . "(['" . $urlJsFile . "'],function(){" . PHP_EOL;

                $jsClose .= '})';
                $i++;
            }
        }
        $jsBefore = c::request()->ajax() ? '' : $this->varJs();

        return $jsBefore . $this->wrapJs($jsOpen . $js . $jsClose);
    }

    public function render($pos, $type = null)
    {
        $moduleThemeScripts = $this->module->getThemeContainer()->getScripts($pos);
        $themeScripts = $this->themeContainer->getScripts($pos);
        $moduleRunTimeScripts = $this->module->getRunTimeContainer()->getScripts($pos);
        $runTimeScripts = $this->runTimeContainer->getScripts($pos);
        $themeScriptArray = [];
        $runtimeScriptArray = [];

        $themeScriptArray = carr::merge($themeScriptArray, $moduleThemeScripts);
        $themeScriptArray = carr::merge($themeScriptArray, $themeScripts);
        $runtimeScriptArray = carr::merge($runtimeScriptArray, $moduleRunTimeScripts);
        $runtimeScriptArray = carr::merge($runtimeScriptArray, $runTimeScripts);

        //do recompile for theme script
        $compileCss = CF::config('assets.css.compile', false);
        if ($compileCss) {
            $cssScriptArray = carr::get($themeScriptArray, static::TYPE_CSS_FILE);

            if (count($cssScriptArray) > 0) {
                $compiledScript = $this->compileCss($cssScriptArray);

                $themeScriptArray[static::TYPE_CSS_FILE] = [$compiledScript];
            }
        }

        //do recompile for theme script
        $compileJs = CF::config('assets.js.compile', false);
        if ($compileJs) {
            $jsScriptArray = carr::get($themeScriptArray, static::TYPE_JS_FILE);

            if (count($jsScriptArray) > 0) {
                $compiledScript = $this->compileJs($jsScriptArray);

                $themeScriptArray[static::TYPE_JS_FILE] = [$compiledScript];
            }
        }

        $scriptArray = carr::merge($themeScriptArray, $runtimeScriptArray);
        $script = '';
        $manager = CManager::instance();
        if ($type == null) {
            $type = self::$allType;
        }
        if (!is_array($type)) {
            $type = [$type];
        }
        foreach ($scriptArray as $scriptType => $scriptValueArray) {
            if (in_array($scriptType, $type)) {
                foreach ($scriptValueArray as $scriptValue) {
                    switch ($scriptType) {
                        case self::TYPE_JS_FILE:
                            if ($scriptValue instanceof CManager_Asset_File_JsFile) {
                                $script .= $scriptValue->render() . PHP_EOL;
                            } else {
                                $urlJsFile = CManager_Asset_Helper::urlJsFile($scriptValue);
                                $script .= '<script src="' . $urlJsFile . '"></script>' . PHP_EOL;
                            }

                            break;
                        case self::TYPE_CSS_FILE:
                            if ($scriptValue instanceof CManager_Asset_File_CssFile) {
                                $script .= $scriptValue->render() . PHP_EOL;
                            } else {
                                $urlCssFile = CManager_Asset_Helper::urlCssFile($scriptValue);
                                $script .= '<link href="' . $urlCssFile . '" rel="stylesheet" />' . PHP_EOL;
                            }

                            break;
                        case self::TYPE_JS:
                            $script .= '<script>' . $scriptValue . '</script>' . PHP_EOL;

                            break;
                        case self::TYPE_CSS:
                            $script .= '<style>' . $scriptValue . '</style>' . PHP_EOL;

                            break;
                        case self::TYPE_PLAIN:
                            $script .= $scriptValue . PHP_EOL;

                            break;
                    }
                }
            }
        }

        return $script;
    }

    public function compileCss($files)
    {
        $options = [];
        $options['type'] = 'css';
        $compiler = new CManager_Asset_Compiler($files, $options);

        return $compiler->compile();
    }

    public function compileJs($files)
    {
        $options = [];
        $options['type'] = 'js';
        $compiler = new CManager_Asset_Compiler($files, $options);

        return $compiler->compile();
    }

    /**
     * @return CImage_OptimizerChain
     */
    public function optimizer()
    {
        return new CImage_OptimizerChain();
    }

    public function findMediaPath($path)
    {
        return CF::findFile('media', $path);
    }

    public function optimize($path)
    {
        if (!CFile::exists($path)) {
            $path = $this->findMediaPath($path);
        }
        if (!CFile::exists($path)) {
            throw new Exception('Path ' . $path . 'not exists');
        }

        return $this->optimizer()->optimize($path);
    }
}
