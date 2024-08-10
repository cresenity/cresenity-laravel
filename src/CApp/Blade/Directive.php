<?php
namespace Cresenity\Laravel\CApp\Blade;

use Illuminate\Support\Str;

/**
 * @author Hery Kurniawan <hery@itton.co.id>
 *
 * @see CApp
 */
class Directive
{
    public static function styles($expression)
    {
        return '{!! Cresenity\Laravel\CApp::instance()->renderStyles() !!}';
    }

    public static function message($expression)
    {
        return '{!! Cresenity\Laravel\CApp\Message::flashAll() !!}';
    }

    public static function scripts($expression)
    {
        return '
        {!! Cresenity\Laravel\CApp::instance()->renderScripts() !!}
        ';
    }

    public static function pageTitle($expression)
    {
        return '{!! Cresenity\Laravel\CApp::instance()->renderPageTitle() !!}';
    }

    public static function title($expression)
    {
        return '{!! Cresenity\Laravel\CApp::instance()->renderTitle() !!}';
    }

    public static function nav($expression)
    {
        return '{!! Cresenity\Laravel\CApp::instance()->renderNavigation(' . $expression . ') !!}';
    }

    public static function seo($expression)
    {
        return '{!! Cresenity\Laravel\CApp::instance()->renderSeo() !!}';
    }

    public static function content($expression)
    {
        return '{!! Cresenity\Laravel\CApp::instance()->renderContent() !!}';
    }

    public static function react($expression)
    {
        return '{!! Cresenity\Laravel\CApp\React::render(' . $expression . ') !!}';
    }

    public static function startReact($expression)
    {
        return '<?php \Cresenity\Laravel\CApp::instance()->startPush(\'capp-react\') ?>';
    }

    public static function endReact($expression)
    {
        return '<?php \Cresenity\Laravel\CApp::instance()->stopPush(\'capp-react\') ?>' . '{!! Cresenity\Laravel\CApp\React::render(' . $expression . ', Cresenity\Laravel\CApp::instance()->yieldPushContent(\'capp-react\')) !!}';
    }

    public static function pushScript($expression)
    {
        return '<?php \Cresenity\Laravel\CApp::instance()->startPush(\'capp-script\') ?>';
    }

    public static function endPushScript($expression)
    {
        return '<?php \Cresenity\Laravel\CApp::instance()->stopPush(\'capp-script\'); ?>';
    }

    public static function prependScript($expression)
    {
        return '<?php \Cresenity\Laravel\CApp::instance()->startPrepend(\'capp-script\'); ?>';
    }

    public static function endPrependScript($expression)
    {
        return '<?php \Cresenity\Laravel\CApp::instance()->stopPrepend(\'capp-script\'); ?>';
    }

    public static function element($expression)
    {
        if (Str::startsWith(trim($expression), 'function')) {
            return "<?php echo \Cresenity\Laravel\CApp::instance()->yieldViewElement(isset(\$__CAppElementView) ? \$__CAppElementView : null, " . $expression . '); ?>';
        }
        $expression = str_replace(['(', ')'], '', $expression);
        $expression = str_replace(['"', '\''], '', $expression);
        $expression = str_replace(',', ' ', $expression);

        return "<?php echo \Cresenity\Laravel\CApp::instance()->yieldViewElement(isset(\$__CAppElementView) ? \$__CAppElementView : null, '" . $expression . "'); ?>";
    }

    public static function directive($expression)
    {
        $expression = str_replace(['(', ')'], '', $expression);
        $expression = str_replace(['"', '\''], '', $expression);
        $expression = str_replace(',', ' ', $expression);
        switch ($expression) {
            case 'styles':
                return static::styles($expression);
            case 'scripts':
                return static::scripts($expression);
            case 'content':
                return static::content($expression);
            case 'pageTitle':
                return static::pageTitle($expression);
            case 'title':
                return static::title($expression);
            default:
                throw new \InvalidArgumentException('Argument ' . $expression . ' is invalid on CApp directive');
        }

        return $expression;
    }

    public static function pwa($expression)
    {
        $expression = str_replace(['(', ')'], '', $expression);
        $expression = str_replace(['"', '\''], '', $expression);
        $expression = str_replace(',', ' ', $expression);

        return (new \Cresenity\Laravel\CApp\PWA\MetaService($expression))->render();
    }

    public static function preloader($expression)
    {
        if (strlen($expression) == 0) {
            $expression = \c::url('media/img/logo.png');
        }

        return <<<HTML
<!-- Cres Preloader Start Here ${expression} -->
<div id="cres-preloader">
    <div class="preloader-container">
        <div class="preloader-loader">
        </div>
        <img src="<?php echo ${expression}; ?>" />
    </div>
</div>

<!-- Cres Preloader End Here -->
HTML;
    }
}
