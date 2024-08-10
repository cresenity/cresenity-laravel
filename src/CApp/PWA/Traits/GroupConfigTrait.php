<?php
namespace Cresenity\Laravel\CApp\PWA\Traits;

use Cresenity\Laravel\CF;

trait GroupConfigTrait {
    public function getGroupConfig($key, $default = null) {
        return CF::config('cresenity.pwa.group.' . $this->group . '.' . $key, $default);
    }
}
