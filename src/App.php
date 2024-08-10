<?php
namespace Cresenity\Laravel;

use Cresenity\Laravel\App\Concern\BreadcrumbTrait;
use Cresenity\Laravel\App\Concern\RendererTrait;
use Cresenity\Laravel\App\Concern\TitleTrait;
use Cresenity\Laravel\App\Concern\ViewTrait;
use Cresenity\Laravel\App\Element as AppElement;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable as IlluminateRenderable;
use Illuminate\Contracts\Support\Responsable;

final class App implements Responsable, IlluminateRenderable, Jsonable
{
    use RendererTrait, BreadcrumbTrait, TitleTrait, ViewTrait;


    private static $instance;

    protected $data = [];
    private $ajaxData = [];

    private $custom_js = '';

    private $custom_header = '';

    private $custom_footer = '';

    private $coreModuleIsRegistered = false;
    /**
     * @var Cresenity\Laravel\App\Element
     */
    protected $element;

    private $additional_head = '';


    private $custom_data = [];
    /**
     * @return \Cresenity\Core\App
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->element = new AppElement();
    }
    public function registerCoreModules($force = false)
    {
        if ($force || !$this->coreModuleIsRegistered) {
            $manager = Manager::instance();
            $theme = Manager::theme()->getCurrentTheme();
            $themeFile = CF::getFile('themes', $theme);
            if (file_exists($themeFile)) {
                $themeData = include $themeFile;
                $moduleArray = carr::get($themeData, 'client_modules');
                $cssArray = carr::get($themeData, 'css');
                $jsArray = carr::get($themeData, 'js');

                if ($moduleArray != null) {
                    foreach ($moduleArray as $module) {
                        $manager->registerThemeModule($module);
                    }
                }

                if ($cssArray != null) {
                    foreach ($cssArray as $css) {
                        $manager->asset()->theme()->registerCssFile($css);
                    }
                }
                if ($jsArray != null) {
                    foreach ($jsArray as $js) {
                        $manager->asset()->theme()->registerJsFiles($js);
                    }
                }
            }

            $manager->registerModule('block-ui');
        }
    }
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return Http::createResponse($this->render());
    }

    public function toArray()
    {
        $data = [];
        $data['title'] = $this->title;
        $message = '';
        $messageOrig = '';
        if (!$this->keepMessage) {
            $messageOrig = CApp_Message::flashAll();
            if ($this->renderMessage) {
                $message = $messageOrig;
            }
        }

        $asset = CManager::asset();
        $html = $this->element->html();
        $js = $this->element->js();

        if (CF::config('app.javascript.minify')) {
            $js = $this->minifyJavascript($js);
        }

        //$js = $asset->renderJsRequire($js, 'cresenity.cf.requireJs');

        $cappScript = $this->yieldPushContent('capp-script');
        //strip cappScript from <script>
        //parse the output of view
        // preg_match_all('#<script>(.*?)</script>#ims', $cappScript, $matches);

        // foreach ($matches[1] as $value) {
        //     $js = $value . $js;
        // }

        //$js .= $cappScript;
        $assetData = [];
        $assetData['js'] = $asset->getAllJsFileUrl();
        $assetData['css'] = $asset->getAllCssFileUrl();
        $data['assets'] = $assetData;
        $data['html'] = $message . $html . $cappScript;
        $data['js'] = base64_encode($js);
        if (CF::config('app.debug')) {
            $data['jsRaw'] = $js;
        }

        //$data['css_require'] = $asset->getAllCssFileUrl();
        $data['message'] = $messageOrig;
        $data['ajaxData'] = $this->ajaxData;
        $data['html'] = mb_convert_encoding($data['html'], 'UTF-8', 'UTF-8');

        return $data;
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        $data = $this->toArray();

        return json_encode($data, $options);
    }
}
