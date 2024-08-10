<?php
namespace Cresenity\Laravel\CApp\PWA;
class MetaService {
    protected $group;

    public function __construct($group) {
        $this->group = $group;
    }

    public function render() {
        return "<?php if(c::app()->pwa('" . $this->group . "')->isEnabled()) { \$config = (new \Cresenity\Laravel\CApp\PWA\ManifestService('" . $this->group . "'))->generate(); echo \$__env->make('cresenity.pwa.meta' , ['group' => '" . $this->group . "', 'config' => \$config])->render(); }  ?>";
    }
}
