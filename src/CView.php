<?php
namespace Cresenity\Laravel;

use Illuminate\View\View as IlluminateView;
use Illuminate\View\Factory as IlluminateViewFactory;

class CView
{

    /**
     * Creates a new CView using the given parameters.
     *
     * @param null|mixed $name
     * @param mixed      $data
     * @param mixed      $mergeData
     *
     * @return IlluminateView|IlluminateViewFactory
     */
    public static function factory($name = null, $data = [], $mergeData = [])
    {
        if ($name == null) {
            return \c::container('view');
        }
        return \c::container('view')->make($name, $data);
        //return new CView($name, $data, $type);
    }

    /**
     * Check a CView is exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function exists($name)
    {
        return self::factory()->exists($name);
    }


    /**
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    public static function blade()
    {
        return \c::container('blade.compiler');
    }

    /**
     * @return \Illuminate\View\FileViewFinder
     */
    public static function finder()
    {
        return \c::container('view.finder');
    }
}
