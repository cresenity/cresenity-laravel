<?php

namespace Cresenity\Laravel\CElement\Traits\Property;

use Cresenity\Laravel\CAjax;
use Cresenity\Laravel\CElement\Element\Depends\DependsOn;
use Cresenity\Laravel\CStringBuilder;

trait DependsOnPropertyTrait
{
    /**
     * @var Cresenity\Laravel\CElement\Element\Depends\DependsOn[]
     */
    protected $dependsOn = [];

    /**
     * @param CRenderable|string $selector
     * @param callable           $resolver
     * @param array              $options
     *
     * @return $this
     */
    public function setDependsOn($selector, $resolver, array $options = [])
    {
        $this->dependsOn[] = new DependsOn($selector, $resolver, $options);

        return $this;
    }

    /**
     * @return Cresenity\Laravel\CElement\Element\Depends\DependsOn[]
     */
    public function getDependsOn()
    {
        return $this->dependsOn;
    }

    public function getDependsOnContentJavascript()
    {
        $js = new CStringBuilder();
        foreach ($this->dependsOn as $dependOn) {
            //we create ajax method

            $dependsOnSelector = $dependOn->getSelector()->getQuerySelector();
            $targetSelector = '#' . $this->id();
            $ajaxMethod = CAjax::createMethod();
            $ajaxMethod->setType('DependsOn');
            $ajaxMethod->setMethod('post');
            $ajaxMethod->setData('dependsOn', serialize($dependOn));
            $ajaxMethod->setData('from', static::class);
            $ajaxUrl = $ajaxMethod->makeUrl();
            $throttle = $dependOn->getThrottle();
            $optionsJson = '{';
            $optionsJson .= "url:'" . $ajaxUrl . "',";
            $optionsJson .= "method:'" . 'post' . "',";
            $optionsJson .= !$dependOn->getBlock() ? 'block: false,' : '';
            $valueScript = $dependOn->getSelector()->getScriptForValue();
            $optionsJson .= 'dataAddition: { value: ' . $valueScript . ' },';
            $optionsJson .= "onSuccess: (data) => {
                cresenity.handleResponse(data, () => {
                    let jQueryTarget = $('" . $targetSelector . "');
                    jQueryTarget.empty();
                    if(typeof data == 'object') {
                        if(data.value) {
                            jQueryTarget.html(data.value);
                        } else {
                            if(typeof data.html === 'undefined') {
                                cresenity.htmlModal(data);
                            } else {
                                jQueryTarget.html(data.html);
                                if (data.js && data.js.length > 0) {
                                    let script = cresenity.base64.decode(data.js);
                                    eval(script);
                                }
                            }
                        }
                    } else {
                        jQueryTarget.html(data);
                    }
                });
            },";
            $optionsJson .= 'handleJsonResponse: true';
            $optionsJson .= '}';

            $optionsJson = str_replace(["\r\n", "\n", "\r"], '', $optionsJson);

            $dependsOnFunctionName = 'dependsOnFunction' . uniqid();
            $js->appendln('
                 let ' . $dependsOnFunctionName . ' = () => {
                     cresenity.ajax(' . $optionsJson . ");
                 };
                 $('" . $dependsOnSelector . "').change(cresenity.debounce(" . $dependsOnFunctionName . ' ,' . $throttle . '));
                 ' . $dependsOnFunctionName . '();
             ');
        }

        return $js->text();
    }

    public function getDependsOnValueJavascript()
    {
        $js = new CStringBuilder();
        foreach ($this->dependsOn as $dependOn) {
            //we create ajax method

            $dependsOnSelector = $dependOn->getSelector();
            $targetSelector = '#' . $this->id();
            $ajaxMethod = CAjax::createMethod();
            $ajaxMethod->setType('DependsOn');
            $ajaxMethod->setMethod('post');
            $ajaxMethod->setData('dependsOn', serialize($dependOn));
            $ajaxMethod->setData('from', static::class);
            $ajaxUrl = $ajaxMethod->makeUrl();
            $throttle = $dependOn->getThrottle();
            $optionsJson = '{';
            $optionsJson .= "url:'" . $ajaxUrl . "',";
            $optionsJson .= "method:'" . 'post' . "',";
            $optionsJson .= !$dependOn->getBlock() ? 'block: false,' : '';

            $optionsJson .= "dataAddition: { value: $('" . $dependsOnSelector . "').val() },";
            $optionsJson .= "onSuccess: (data) => {
                let jQueryTarget = $('" . $targetSelector . "');

                if(typeof data == 'object') {
                    if(typeof data.value !== 'undefined') {
                        jQueryTarget.val(data.value);
                    } else {
                        jQueryTarget.val(JSON.stringify(data));
                    }
                } else {
                    jQueryTarget.val(data);
                }


            },";
            $optionsJson .= 'handleJsonResponse: true';
            $optionsJson .= '}';

            $optionsJson = str_replace(["\r\n", "\n", "\r"], '', $optionsJson);

            $dependsOnFunctionName = 'dependsOnFunction' . uniqid();
            $js->appendln('
                 let ' . $dependsOnFunctionName . ' = () => {
                     cresenity.ajax(' . $optionsJson . ");
                 };
                 $('" . $dependsOnSelector . "').change(cresenity.debounce(" . $dependsOnFunctionName . ' ,' . $throttle . '));
                 ' . $dependsOnFunctionName . '();
             ');
        }

        return $js->text();
    }
}
