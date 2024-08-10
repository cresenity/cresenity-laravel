<?php
namespace Cresenity\Laravel\App;

use Cresenity\Laravel\Observable;

class Element extends Observable {
    public function __construct($id = '') {
        parent::__construct($id);
    }
}
