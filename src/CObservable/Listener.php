<?php

namespace Cresenity\Laravel\CObservable;

use Cresenity\Laravel\CStringBuilder;

class Listener extends ListenerAbstract
{
    protected $confirm;

    protected $confirmMessage;

    protected $noDouble;

    public function __construct($owner, $event = 'click')
    {
        parent::__construct($owner);
        $this->confirm = false;
        $this->confirmMessage = '';
        $this->noDouble = false;
        $this->event = $event;
    }

    public static function factory($owner, $event)
    {
        return new Listener($owner, $event);
    }

    public function setConfirm($bool)
    {
        $this->confirm = $bool;

        return $this;
    }

    public function setNoDouble($bool)
    {
        $this->noDouble = $bool;

        return $this;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setConfirmMessage($message)
    {
        $this->confirmMessage = $message;

        return $this;
    }

    public function getBodyJs()
    {
        $startScript = "
            var thiselm=jQuery(this);
            var clicked = thiselm.attr('data-clicked');
        ";
        if ($this->noDouble) {
            $startScript .= '
                if(clicked) return false;
            ';
        }
        $startScript .= "
            thiselm.attr('data-clicked','1');
        ";
        $handlersScript = '';
        foreach ($this->handlers as $handler) {
            $handlersScript .= $handler->js();
        }
        $confirmStartScript = '';
        $confirmEndScript = '';
        if ($this->confirm) {
            $confirmMessage = $this->confirmMessage;
            if (strlen($confirmMessage) == 0) {
                $confirmMessage = \c::__('app.confirm:areYouSure');
            }
            $confirmStartScript = "
                window.cresenity.confirm({owner:thiselm, message:'" . \c::e($confirmMessage) . "',confirmCallback: function(confirmed) {
                    if(confirmed) {
            ";

            $confirmEndScript = "
                    } else {
                        thiselm.removeAttr('data-clicked');
                    }
                    setTimeout(function() {
                        var modalExists = $('.modal:visible').length > 0;
                        if (!modalExists) {
                            $('body').removeClass('modal-open');
                        } else {
                            $('body').addClass('modal-open');
                        }
                    },750);
                }});
            ";
        }
        $compiledJs = $startScript . $confirmStartScript . $handlersScript . $confirmEndScript;

        return $compiledJs;
    }

    public function js($indent = 0)
    {
        $js = new CStringBuilder();
        $js->setIndent($indent);

        $compiledJs = $this->getBodyJs();

        $eventParameterImploded = implode(',', $this->eventParameters);
        if ($this->event == 'lazyload') {
            $js->append("
                jQuery(window).ready(function() {
                    if (jQuery('#" . $this->owner . "')[0].getBoundingClientRect().top < (jQuery(window).scrollTop() + jQuery(window).height())) {
                        " . $compiledJs . "
                    }
                });
                jQuery(window).scroll(function() {
                    if (jQuery('#" . $this->owner . "')[0].getBoundingClientRect().top < (jQuery(window).scrollTop() + jQuery(window).height())) {
                        " . $compiledJs . '
                    }
                });
            ');
        } else {
            $js->append("
                jQuery('#" . $this->owner . "')." . $this->event . '(function(' . $eventParameterImploded . ') {
                    ' . $compiledJs . '
                });
            ');
        }

        return $js->text();
    }
}
