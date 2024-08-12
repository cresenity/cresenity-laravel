<?php
namespace Cresenity\Laravel\CElement\Component\Blockly;

use Illuminate\Support\Arr;

class Helper
{
    public static function buildDefaultXmlForFunction($functionName, $arguments = [], $options = [])
    {
        $x = Arr::get($options, 'x', 70);
        $y = Arr::get($options, 'y', 70);

        $openXml = '<xml xmlns="https://developers.google.com/blockly/xml">';
        $closeXml = '</xml>';
        $openVariables = '<variables>';
        $closeVariables = '</variables>';
        $openBlock = '<block type="procedures_defreturn" x="' . $x . '" y="' . $y . '" deletable="false">';
        $closeBlock = '</block>';
        $openMutation = '<mutation>';
        $openMutation = '</mutation>';

        $variablesXml = '';
        $argsXml = '';
        $fieldXml = '<field name="NAME">' . $functionName . '</field>';
        foreach ($arguments as $arg) {
            $argsXml .= '<arg name="' . $arg . '"/>';
            $variablesXml = '<variable>' . $arg . '</variable>';
        }
        $argsXml = '<mutation>' . $argsXml . '</mutation>';

        return $openXml . $openBlock . $argsXml . $fieldXml . $closeBlock . $closeXml;
    }
}
