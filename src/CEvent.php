<?php



use Illuminate\Events\Dispatcher;

class CEvent {
    protected static $dispatcher;

    /**
     * @return CEvent_Dispatcher
     */
    public static function dispatcher() {
        if (self::$dispatcher == null) {
            self::$dispatcher = static::createDispatcher();
        }

        return self::$dispatcher;
    }

    /**
     * @return Illuminate\Events\Dispatcher
     */
    public static function createDispatcher() {
        return new Dispatcher();
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
        $event = carr::get($args, 0);
        $payload = array_slice($args, 1);
        static::dispatcher()->dispatch($event, $payload);
    }
}
