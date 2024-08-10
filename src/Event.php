<?php
namespace Cresenity\Laravel;

use Illuminate\Events\Dispatcher;

class Event
{
    protected static $dispatcher;

    /**
     * @return Illuminate\Events\Dispatcher
     */
    public static function dispatcher()
    {
        return \c::container('events');
    }
}
