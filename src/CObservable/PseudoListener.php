<?php

namespace Cresenity\Laravel\CObservable;

use Cresenity\Laravel\CStringBuilder;

class PseudoListener extends ListenerAbstract
{
    public function js($indent = 0)
    {
        $js = new CStringBuilder();
        $js->setIndent($indent);

        $handlersScript = '';
        foreach ($this->handlers as $handler) {
            $handlersScript .= $handler->js();
        }
        $eventParameterImploded = implode(',', $this->eventParameters);

        $startScript = 'function(' . $eventParameterImploded . ') {';
        $endScript = '}';
        $compiledJs = $startScript . $handlersScript . $endScript;
        $js->append($compiledJs);

        return $js->text();
    }
}
