<?php

use Illuminate\Contracts\Support\Responsable;
use Cresenity\CresenityLaravel\CApp\Concern\ViewTrait;
use Cresenity\CresenityLaravel\CApp\Concern\RendererTrait;

class CApp implements Responsable {
    use CApp_Concern_RendererTrait;
    use CApp_Concern_ViewTrait;

    protected $id;

    private static $instance;

    public static function instance() {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct() {
        $this->id = 'capp';
    }

    public function toResponse($request) {
        if ($request->ajax()) {
            return response()->json($this);
        }

        return response($this->render());
    }
}
