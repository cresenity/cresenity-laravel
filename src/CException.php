<?php

namespace Cresenity\Laravel;

class CException {
    /**
     * @var array PHP error code => human readable name
     */
    public static $phpErrors = [
        E_ERROR => 'Fatal Error',
        E_USER_ERROR => 'User Error',
        E_PARSE => 'Parse Error',
        E_WARNING => 'Warning',
        E_USER_WARNING => 'User Warning',
        E_STRICT => 'Strict',
        E_NOTICE => 'Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
    ];

    protected static $exceptionHandler;
    /**
     * @return \CException_ExceptionHandler
     */
    public static function exceptionHandler() {
        if (static::$exceptionHandler == null) {
            // $exceptionHandlerClass = CF::config('app.classes.exception_handler', CException_ExceptionHandler::class);
            static::$exceptionHandler = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        }

        return static::$exceptionHandler;
    }
}
