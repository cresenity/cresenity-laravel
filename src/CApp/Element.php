<?php
namespace Cresenity\Laravel\CApp;

use Cresenity\Laravel\CObservable;

class Element extends CObservable
{
    public function __construct($id = '')
    {
        parent::__construct($id);
    }
}
