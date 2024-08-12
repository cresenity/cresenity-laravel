<?php
namespace Cresenity\Laravel\CApp\Concern;

use Cresenity\Laravel\CApp\Blade\Directive;
use Cresenity\Laravel\CElement\Element\FormInput\Number;
use Cresenity\Laravel\CElement\Element\FormInput\Text;
use Cresenity\Laravel\CElement\Element\FormInput\Textarea;
use Cresenity\Laravel\CManager;
use Cresenity\Laravel\CView;

trait BootstrapTrait
{
    protected static $registerControlBooted = false;

    protected static $registerBladeBooted = false;


    public static function registerBlade()
    {
        if (!static::$registerBladeBooted) {
            CView::blade()->directive('CApp', [Directive::class, 'directive']);
            CView::blade()->directive('CAppStyles', [Directive::class, 'styles']);
            CView::blade()->directive('CAppScripts', [Directive::class, 'scripts']);
            CView::blade()->directive('CAppPageTitle', [Directive::class, 'pageTitle']);
            CView::blade()->directive('CAppTitle', [Directive::class, 'title']);
            CView::blade()->directive('CAppNav', [Directive::class, 'nav']);
            CView::blade()->directive('CAppSeo', [Directive::class, 'seo']);
            CView::blade()->directive('CAppContent', [Directive::class, 'content']);
            CView::blade()->directive('CAppPushScript', [Directive::class, 'pushScript']);
            CView::blade()->directive('CAppEndPushScript', [Directive::class, 'endPushScript']);
            CView::blade()->directive('CAppPrependScript', [Directive::class, 'prependScript']);
            CView::blade()->directive('CAppEndPrependScript', [Directive::class, 'endPrependScript']);
            CView::blade()->directive('CAppElement', [Directive::class, 'element']);
            CView::blade()->directive('CAppMessage', [Directive::class, 'message']);
            CView::blade()->directive('CAppPWA', [Directive::class, 'pwa']);
            CView::blade()->directive('CAppReact', [Directive::class, 'react']);
            CView::blade()->directive('CAppStartReact', [Directive::class, 'startReact']);
            CView::blade()->directive('CAppEndReact', [Directive::class, 'endReact']);
            CView::blade()->directive('CAppPreloader', [Directive::class, 'preloader']);
            static::$registerBladeBooted = true;
        }
    }

    public static function registerControl()
    {
        if (!static::$registerControlBooted) {
            // CFBenchmark::start('CApp.RegisterControl');
            $manager = CManager::instance();
            $manager->registerControls([
                'text' => Text::class,
                'textarea' => Textarea::class,
                'number' => Number::class,
                'email' => CElement_FormInput_Email::class,
                'datepicker' => CElement_FormInput_Date::class,
                'date' => CElement_FormInput_Date::class,
                'material-datetime' => CElement_FormInput_DateTime_MaterialDateTime::class,
                'daterange-picker' => CElement_FormInput_DateRange::class,
                'daterange-dropdown' => CElement_FormInput_DateRange_Dropdown::class,
                'daterange-button' => CElement_FormInput_DateRange_DropdownButton::class,
                'currency' => CElement_FormInput_Currency::class,
                'auto-numeric' => CElement_FormInput_AutoNumeric::class,
                'time' => CElement_FormInput_Time::class,
                'timepicker' => CElement_FormInput_Time::class,
                'clock' => CElement_FormInput_Clock::class,
                'clockpicker' => CElement_FormInput_Clock::class,
                'image' => CElement_FormInput_Image::class,
                'image-ajax' => CElement_FormInput_ImageAjax::class,
                'multi-image-ajax' => CElement_FormInput_MultipleImageAjax::class,
                'file' => CElement_FormInput_File::class,
                'file-ajax' => CElement_FormInput_FileAjax::class,
                'multi-file-ajax' => CElement_FormInput_MultipleFileAjax::class,
                'password' => CElement_FormInput_Password::class,
                'select' => CElement_FormInput_Select::class,
                'minicolor' => CElement_FormInput_MiniColor::class,
                'map-picker' => CElement_FormInput_MapPicker::class,
                'hidden' => CElement_FormInput_Hidden::class,
                'select-tag' => CElement_FormInput_SelectTag::class,
                'selectsearch' => CElement_FormInput_SelectSearch::class,
                'checkbox' => CElement_FormInput_Checkbox::class,
                'checkbox-list' => CElement_FormInput_CheckboxList::class,
                'switcher' => CElement_FormInput_Checkbox_Switcher::class,
                'summernote' => CElement_FormInput_Textarea_Summernote::class,
                'radio' => CElement_FormInput_Radio::class,
                'label' => CElement_FormInput_Label::class,
                'quill' => CElement_FormInput_Textarea_Quill::class,
                'ckeditor' => CFormInputCKEditor::class,
                //'filedrop' => CFormInputFileDrop::class,
                //'slider' => CFormInputSlider::class,
                //'tooltip' => CFormInputTooltip::class,
                'fileupload' => CElement_FormInput_MultipleImageAjax::class,
                'wysiwyg' => CFormInputWysiwyg::class,
            ]);

            // CFBenchmark::stop('CApp.RegisterControl');
            static::$registerControlBooted = true;
        }
    }
}
