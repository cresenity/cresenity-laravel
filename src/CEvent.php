<?php
namespace Cresenity\Laravel;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;

class CEvent
{
    protected static $dispatcher;

    /**
     * @return Illuminate\Events\Dispatcher
     */
    public static function dispatcher()
    {
        return \c::container('events');
    }

    /**
     * Dispatch an event and call the listeners.
     *
     * //@param string|object $event
     * //@param mixed         $payload
     * //@param bool          $halt
     *
     * @return null|array
     */
    public static function dispatch() {
        $args = func_get_args();
        $event = Arr::get($args, 0);
        $payload = array_slice($args, 1);
        static::dispatcher()->dispatch($event, $payload);
    }
}
