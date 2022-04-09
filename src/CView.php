<?php



class CView {
    public static function factory($viewName = null) {
        return view($viewName);
    }

    public static function exists($viewName) {
        return static::factory()->exists($viewName);
    }
}
