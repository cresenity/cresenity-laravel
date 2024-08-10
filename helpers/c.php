<?php


/**
 * Common helper class.
 */
use Faker\Factory as FackerFactory;
use Opis\Closure\SerializableClosure;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Cresenity\Laravel\App;
use Cresenity\Laravel\CConfig;
use Cresenity\Laravel\CHTTP;
use Cresenity\Laravel\CRouting;
use Cresenity\Laravel\CTranslation;
use Illuminate\Container\Container;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Translation\Translator;

//@codingStandardsIgnoreStart
class c
{
    //@codingStandardsIgnoreEnd

    /**
     * @param string $str
     *
     * @return string
     */
    public static function fixPath($str)
    {
        $str = str_replace(['/', '\\'], DS, $str);

        return rtrim($str, DS) . DS;
    }

    public static function urShift($a, $b)
    {
        if ($b == 0) {
            return $a;
        }

        return ($a >> $b) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ($b - 1));
    }

    public static function baseIteratee($value)
    {
        if (\is_callable($value)) {
            return $value;
        }
        if (null === $value) {
            return ['c', 'identity'];
        }
        if (\is_array($value)) {
            return 2 === \count($value) && [0, 1] === \array_keys($value) ? static::baseMatchesProperty($value[0], $value[1]) : static::baseMatches($value);
        }

        return static::property($value);
    }

    public static function baseMatchesProperty($property, $source)
    {
        return function ($value, $index, $collection) use ($property, $source) {
            $propertyVal = static::property($property);

            return static::isEqual($propertyVal($value, $index, $collection), $source);
        };
    }

    public static function baseMatches($source)
    {
        return function ($value, $index, $collection) use ($source) {
            if ($value === $source || static::isEqual($value, $source)) {
                return true;
            }
            if (\is_array($source) || $source instanceof \Traversable) {
                foreach ($source as $k => $v) {
                    $propK = c::property($k);
                    if (!static::isEqual($propK($value, $index, $collection), $v)) {
                        return false;
                    }
                }

                return true;
            }

            return false;
        };
    }

    public static function isEqual($value, $other)
    {
        $factory = CComparator::createFactory();
        $comparator = $factory->getComparatorFor($value, $other);

        try {
            $comparator->assertEquals($value, $other);

            return true;
        } catch (CComparator_Exception_ComparisonFailureException $failure) {
            return false;
        }
    }

    /**
     * Creates a function that returns the value at `path` of a given object.
     *
     * @param array|string $path the path of the property to get
     *
     * @return callable returns the new accessor function
     *
     * @example
     * <code>
     * $objects = [
     *   [ 'a' => [ 'b' => 2 ] ],
     *   [ 'a' => [ 'b' => 1 ] ]
     * ];
     *
     * carr::map($objects, property('a.b'));
     * // => [2, 1]
     *
     * carr::map(sortBy($objects, property(['a', 'b'])), 'a.b');
     * // => [1, 2]
     * </code>
     */
    public static function property($path)
    {
        $propertyAccess = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidIndex()
            ->getPropertyAccessor();

        return function ($value, $index = 0, $collection = []) use ($path, $propertyAccess) {
            $path = \implode('.', (array) $path);
            if (\is_array($value)) {
                if (false !== \strpos($path, '.')) {
                    $paths = \explode('.', $path);
                    foreach ($paths as $path) {
                        $propPath = static::property($path);
                        $value = $propPath($value, $index, $collection);
                    }

                    return $value;
                }

                if (\is_string($path) && $path[0] !== '[' && $path[strlen($path) - 1] !== ']') {
                    $path = '[' . $path . ']';
                }
            }

            try {
                return $propertyAccess->getValue($value, $path);
            } catch (NoSuchPropertyException $e) {
                return null;
            } catch (NoSuchIndexException $e) {
                return null;
            }
        };
    }

    /**
     * Create a collection from the given value.
     *
     * @param mixed $value
     *
     * @return CCollection
     */
    public static function collect($value = null)
    {
        return new CCollection($value);
    }

    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param mixed         $value
     * @param null|callable $callback
     *
     * @return mixed
     */
    public static function tap($value, $callback = null)
    {
        if (is_null($callback)) {
            return new CBase_HigherOrderTapProxy($value);
        }

        $callback($value);

        return $value;
    }

    /**
     * Get the class "basename" of the given object / class.
     *
     * @param string|object $class
     *
     * @return string
     */
    public static function classBasename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        $basename = basename(str_replace('\\', '/', $class));
        $basename = carr::last(explode('_', $basename));

        return $basename;
    }

    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param string $trait
     *
     * @return array
     */
    public static function traitUsesRecursive($trait)
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits += self::traitUsesRecursive($trait);
        }

        return $traits;
    }

    /**
     * Returns all traits used by a class, its subclasses and trait of their traits.
     *
     * @param object|string $class
     *
     * @return array
     */
    public static function classUsesRecursive($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += self::traitUsesRecursive($class);
        }

        return array_unique($results);
    }

    /**
     * Returns true of traits is used by a class, its subclasses and trait of their traits.
     *
     * @param object|string $class
     * @param string        $trait
     *
     * @return array
     */
    public static function hasTrait($class, $trait)
    {
        return in_array($trait, static::classUsesRecursive($class));
    }

    /**
     * Catch a potential exception and return a default value.
     *
     * @param callable $callback
     * @param mixed    $rescue
     * @param bool     $report
     *
     * @return mixed
     */
    public static function rescue(callable $callback, $rescue = null, $report = true)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($report) {
                static::report($e);
            }

            return static::value($rescue);
        }
    }

    /**
     * Return the given value, optionally passed through the given callback.
     *
     * @param mixed         $value
     * @param null|callable $callback
     *
     * @return mixed
     */
    public static function with($value, callable $callback = null)
    {
        return is_null($callback) ? $value : $callback($value);
    }

    /**
     * Report an exception.
     *
     * @param \Throwable|string $exception
     *
     * @return void
     */
    public static function report($exception)
    {
        if (is_string($exception)) {
            $exception = new Exception($exception);
        }

        CException::exceptionHandler()->report($exception);
    }

    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function value($value, ...$args)
    {
        if ($value instanceof SerializableClosure) {
            return $value->__invoke(...$args);
        }

        return $value instanceof Closure ? $value(...$args) : $value;
    }

    //@codingStandardsIgnoreStart

    /**
     * Dispatch an event and call the listeners.
     *
     * @param string|object $event
     * @param mixed         $payload
     * @param bool          $halt
     *
     * @return null|array|CEvent_Dispatcher
     */
    public static function event(...$args)
    {
        if (count($args) == 0) {
            return CEvent::dispatcher();
        }

        return CEvent::dispatch(...$args);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param null|string $message
     * @param array       $context
     *
     * @return null|\CLogger
     */
    public static function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return CLogger::logger();
        }

        return CLogger::logger()->debug($message, $context);
    }

    //@codingStandardsIgnoreEnd

    /**
     * Create a new Carbon instance for the current time.
     *
     * @param null|\DateTimeZone|string $tz
     *
     * @return CCarbon|\Carbon\Carbon
     */
    public static function now($tz = null)
    {
        return CCarbon::now($tz);
    }

    /**
     * Create a new Carbon instance for the current date.
     *
     * @param null|\DateTimeZone|string $tz
     *
     * @return CCarbon
     */
    public static function today($tz = null)
    {
        return CCarbon::today($tz);
    }

    public static function hrtime($getAsNumber = false)
    {
        if (function_exists('hrtime')) {
            return hrtime($getAsNumber);
        }

        if ($getAsNumber) {
            return microtime(true) * 1e+6;
        }
        $mt = microtime();
        $s = floor($mt);

        return [$s, ($mt - $s) * 1e+6];
    }

    /**
     * @param string $str
     *
     * @deprecated use c::e
     *
     * @return string
     */
    public static function html($str)
    {
        return c::e($str);
    }

    public static function dirname($path, $count = 1)
    {
        if ($count > 1) {
            return dirname(static::dirname($path, --$count));
        } else {
            return dirname($path);
        }
    }

    /**
     * Provide access to optional objects.
     *
     * @param mixed         $value
     * @param null|callable $callback
     *
     * @return mixed
     */
    public static function optional($value = null, callable $callback = null)
    {
        if (is_null($callback)) {
            return new COptional($value);
        }
        if (!is_null($value)) {
            return $callback($value);
        }
    }

    /**
     * Encode HTML special characters in a string.
     *
     * @param CBase_DeferringDisplayableValue|CInterface_Htmlable|string $value
     * @param bool                                                       $doubleEncode
     *
     * @return string
     */
    public static function e($value, $doubleEncode = true)
    {
        if ($value instanceof CBase_DeferringDisplayableValueInterface) {
            $value = $value->resolveDisplayableValue();
        }

        if ($value instanceof CInterface_Htmlable) {
            return $value->toHtml();
        }

        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    /**
     * @param string $string
     *
     * @return CBase_String|\cstr
     */
    public static function str($string = null)
    {
        if (is_null($string)) {
            return new CBase_ForwarderStaticClass(cstr::class);
        }

        return cstr::of($string);
    }

    /**
     * Throw the given exception unless the given condition is true.
     *
     * @param mixed             $condition
     * @param \Throwable|string $exception
     * @param array             ...$parameters
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public static function throwUnless($condition, $exception = 'RuntimeException', ...$parameters)
    {
        c::throwIf(!$condition, $exception, ...$parameters);

        return $condition;
    }

    /**
     * Throw the given exception if the given condition is true.
     *
     * @param mixed             $condition
     * @param \Throwable|string $exception
     * @param array             ...$parameters
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public static function throwIf($condition, $exception = 'RuntimeException', ...$parameters)
    {
        if ($condition) {
            if (is_string($exception) && class_exists($exception)) {
                $exception = new $exception(...$parameters);
            }

            throw is_string($exception) ? new RuntimeException($exception) : $exception;
        }

        return $condition;
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function env($key, $default = null)
    {
        return CEnv::get($key, $default);
    }

    /**
     * Translate the given message.
     *
     * @param null|string $key
     * @param array       $replace
     * @param null|string $locale
     *
     * @return null|Translator|string|array
     */
    public static function trans($key = null, $replace = [], $locale = null)
    {
        if ($key === null) {
            return CTranslation::translator();
        }
        if ($replace === null) {
            $replace = [];
        }

        return CTranslation::translator()->get($key, $replace, $locale);
    }

    //@codingStandardsIgnoreStart

    /**
     * Translate the given message.
     *
     * @param null|string $key
     * @param array       $replace
     * @param null|string $locale
     *
     * @return null|string|array
     */
    public static function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        return static::trans($key, $replace, $locale);
    }

    //@codingStandardsIgnoreEnd

    /**
     * @param null|array|string $key
     * @param null|mixed        $default
     *
     * @return CSession_Store|mixed
     */
    public static function session($key = null, $default = null)
    {
        if ($key === null) {
            return CSession::store();
        }
        if (is_array($key)) {
            return CSession::store()->put($key);
        }

        return CSession::store()->get($key, $default);
    }

    /**
     * Generate a url for the application.
     *
     * @param null|string $path
     * @param mixed       $parameters
     * @param null|bool   $secure
     *
     * @return UrlGenerator|string
     */
    public static function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return CRouting::urlGenerator();
        }

        return CRouting::urlGenerator()->to($path, $parameters, $secure);
    }

    /**
     * @return CStorage
     */
    public static function storage()
    {
        return CStorage::instance();
    }

    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return CValidation_Validator|CValidation_Factory
     */
    public static function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = CValidation::factory();

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param null|string                $view
     * @param CInterface_Arrayable|array $data
     * @param array                      $mergeData
     *
     * @return CView_View|CView_Factory
     */
    public static function view($view = null, $data = [], $mergeData = [])
    {
        $factory = CView::factory();

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }

    /**
     * Throw an HttpException with the given data unless the given condition is true.
     *
     * @param bool                                       $boolean
     * @param CHTTP_Response|\CInterface_Responsable|int $code
     * @param string                                     $message
     * @param array                                      $headers
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public static function abortUnless($boolean, $code, $message = '', array $headers = [])
    {
        if (!$boolean) {
            static::abort($code, $message, $headers);
        }
    }

    /**
     * Displays a 404 page.
     *
     * @param string $page     URI of page
     * @param string $template custom template
     *
     * @return void
     */
    public static function show404($page = false, $template = false)
    {
        return static::abort(404);
    }

    public static function abort($code, $message = '', array $headers = [])
    {
        if ($code instanceof CHTTP_Response) {
            throw new CHTTP_Exception_ResponseException($code);
        }
        if ($code instanceof CInterface_Responsable) {
            throw new CHTTP_Exception_ResponseException($code->toResponse(CHTTP::request()));
        }

        if ($code == 404) {
            throw new CHTTP_Exception_NotFoundHttpException($message);
        }
        if ($code == 410) {
            throw new CHTTP_Exception_GoneHttpException($message);
        }

        throw new CHTTP_Exception_HttpException($code, $message, null, $headers);
    }

    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param bool                                       $boolean
     * @param CHTTP_Response|\CInterface_Responsable|int $code
     * @param string                                     $message
     * @param array                                      $headers
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public static function abortIf($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            static::abort($code, $message, $headers);
        }
    }

    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param null|array|string $key
     * @param mixed             $default
     *
     * @return CHTTP_Request|string|array
     *
     * @phpstan-return ($key is null ? CHTTP_Request : string|array)
     */
    public static function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return CHTTP::request();
        }

        if (is_array($key)) {
            return CHTTP::request()->only($key);
        }

        $value = CHTTP::request()->__get($key);

        return is_null($value) ? c::value($default) : $value;
    }

    /**
     * Return a new response from the application.
     *
     * @param null|CView|string|array $content
     * @param int                     $status
     * @param array                   $headers
     *
     * @return CHTTP_Response|CHTTP_ResponseFactory
     */
    public static function response($content = '', $status = 200, array $headers = [])
    {
        $factory = CHTTP::responseFactory();

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }

    /**
     * Determine if the given value is "blank".
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function blank($value)
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof Countable) {
            return count($value) === 0;
        }
        if ($value instanceof CBase_String) {
            return trim((string) $value) === '';
        }

        return empty($value);
    }

    /**
     * Determine if a value is "filled".
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function filled($value)
    {
        return !static::blank($value);
    }

    /**
     * Get an instance of the redirector.
     *
     * @param null|string $to
     * @param int         $status
     * @param array       $headers
     * @param null|bool   $secure
     *
     * @return CHTTP_Redirector|CHTTP_RedirectResponse
     *
     * @phpstan-return ($to is null ? CHTTP_Redirector : CHTTP_RedirectResponse)
     */
    public static function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if ($to instanceof CController) {
            $to = $to->controllerUrl();
        }
        if ($to === null) {
            return CHTTP::redirector();
        }

        return CHTTP::redirector()->to($to, $status, $headers, $secure);
    }

    /**
     * Get hash manager instance.
     *
     * @param null|string $hasher
     *
     * @return CCrypt_HashManager
     */
    public static function hash($hasher = null)
    {
        return CCrypt_HashManager::instance($hasher);
    }

    /**
     * Get router instance.
     *
     * @return CRouting_Router
     */
    public static function router()
    {
        return CRouting_Router::instance();
    }

    /**
     * Create an Fluent object from the given value.
     *
     * @param object|array $value
     *
     * @return \CBase_Fluent
     */
    public static function fluent($value)
    {
        return new CBase_Fluent($value);
    }

    /**
     * Return a new literal or anonymous object using named arguments.
     *
     * @return \stdClass
     */
    public static function literal(...$arguments)
    {
        if (count($arguments) === 1 && array_is_list($arguments)) {
            return $arguments[0];
        }

        return (object) $arguments;
    }

    /**
     * Find route from uri.
     *
     * @param string $uri
     *
     * @return null|CRouting_Route
     */
    public static function findRoute($uri)
    {
        return static::router()->getRoutes()->match(CHTTP_Request::create($uri));
    }

    /**
     * Generate the URL to a named route.
     *
     * @param array|string $name
     * @param mixed        $parameters
     * @param bool         $absolute
     *
     * @return string
     */
    public static function route($name, $parameters = [], $absolute = true)
    {
        return static::url()->route($name, $parameters, $absolute);
    }

    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool  $serialize
     *
     * @return string
     */
    public static function encrypt($value, $serialize = true)
    {
        return CCrypt::encrypter()->encrypt($value, $serialize);
    }

    /**
     * Decrypt the given value.
     *
     * @param string $value
     * @param bool   $unserialize
     *
     * @return mixed
     */
    public static function decrypt($value, $unserialize = true)
    {
        return CCrypt::encrypter()->decrypt($value, $unserialize);
    }

    /**
     * Dump variable.
     *
     * @param mixed $var
     *
     * @return void
     */
    public static function dump($var)
    {
        foreach (func_get_args() as $var) {
            VarDumper::dump($var);
        }
    }

    /**
     * Retry an operation a given number of times.
     *
     * @param int           $times
     * @param callable      $callback
     * @param int|\Closure  $sleepMilliseconds
     * @param null|callable $when
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function retry($times, callable $callback, $sleepMilliseconds = 0, $when = null)
    {
        $attempts = 0;
        $backoff = [];

        if (is_array($times)) {
            $backoff = $times;

            $times = count($times) + 1;
        }

        beginning:
        $attempts++;
        $times--;

        try {
            return $callback($attempts);
        } catch (Exception $e) {
            if ($times < 1 || ($when && !$when($e))) {
                throw $e;
            }
            $sleepMilliseconds = $backoff[$attempts - 1] ?? $sleepMilliseconds;
            if ($sleepMilliseconds) {
                CBase_Sleep::usleep(c::value($sleepMilliseconds, $attempts, $e) * 1000);
            }

            goto beginning;
        }
    }

    /**
     * Generate an media path for the application.
     *
     * @param string    $path
     * @param null|bool $secure
     *
     * @return string
     */
    public static function media($path = '', $secure = null)
    {
        return c::url()->asset($path, $secure);
    }

    /**
     * Retrieve an old input item.
     *
     * @param null|string $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public static function old($key = null, $default = null)
    {
        return CHTTP::request()->old($key, $default);
    }

    /**
     * Get the available auth instance.
     *
     * @param null|string $guard
     *
     * @return CAuth_Manager|CAuth_Contract_GuardInterface|CAuth_Contract_StatefulGuardInterface
     */
    public static function auth($guard = null)
    {
        if (is_null($guard)) {
            return CAuth::manager();
        }

        return CAuth::manager()->guard($guard);
    }

    /**
     * Generate a CSRF token form field.
     *
     * @return CBase_HtmlString
     */
    public static function csrfField()
    {
        return new CBase_HtmlString('<input type="hidden" name="_token" value="' . static::csrfToken() . '">');
    }

    /**
     * Get the CSRF token value.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function csrfToken()
    {
        $session = c::session();

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }

    /**
     * Get the available container instance.
     *
     * @param null|string $abstract
     * @param array       $parameters
     *
     * @return CContainer_Container|mixed
     */
    public static function container($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }
        if ($resolved = Container::getInstance()->get($abstract)) {
            return $resolved;
        }

        return Container::getInstance()->make($abstract, $parameters);
    }

    /**
     * @param null|string $key
     * @param mixed       $default
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function config($key = null, $default = null)
    {
        if ($key == null) {
            return CConfig::repository();
        }

        return CConfig::repository()->get($key, $default);
    }

    /**
     * Get the CApp instance.
     *
     * @return \Cresenity\Laravel\App
     */
    public static function app()
    {
        return App::instance();
    }

    /**
     * Create Div instance.
     *
     * @param null|string $id
     *
     * @return \CElement_Element_Div
     */
    public static function div($id = null)
    {
        return CElement_Element_Div::factory($id);
    }

    /**
     * Get the CApp instance.
     *
     * @return \CManager
     */
    public static function manager()
    {
        return CManager::instance();
    }

    /**
     * Get the database connection instance.
     *
     * @param null|string $name
     *
     * @return \CDatabase_Connection
     */
    public static function db($name = null)
    {
        return CDatabase::manager()->connection($name);
    }

    public static function userAgent()
    {
        return !empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param mixed                 $target
     * @param null|string|array|int $key
     * @param mixed                 $default
     *
     * @return mixed
     */
    public static function get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {
                if ($target instanceof CCollection) {
                    $target = $target->all();
                } elseif (!is_iterable($target)) {
                    return c::value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = static::get($item, $key);
                }

                return in_array('*', $key) ? carr::collapse($result) : $result;
            }
            $segmentMap = [
                '\*' => '*',
                '\{first}' => '{first}',
                '{first}' => array_key_first(is_array($target) ? $target : c::collect($target)->all()),
                '\{last}' => '{last}',
                '{last}' => array_key_last(is_array($target) ? $target : c::collect($target)->all()),
            ];
            $segment = carr::get($segmentMap, $segment, $segment);

            if (carr::accessible($target) && carr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return static::value($default);
            }
        }

        return $target;
    }

    /**
     * Set an item on an array or object using dot notation.
     *
     * @param mixed        $target
     * @param string|array $key
     * @param mixed        $value
     * @param bool         $overwrite
     *
     * @return mixed
     */
    public static function set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if (!carr::accessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    static::set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (carr::accessible($target)) {
            if ($segments) {
                if (!carr::exists($target, $segment)) {
                    $target[$segment] = [];
                }

                static::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || !carr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (!isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                static::set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || !isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ($segments) {
                static::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }

    public static function forget(&$target, $key)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*' && carr::accessible($target)) {
            if ($segments) {
                foreach ($target as &$inner) {
                    self::forget($inner, $segments);
                }
            }
        } elseif (carr::accessible($target)) {
            if ($segments && carr::exists($target, $segment)) {
                self::forget($target[$segment], $segments);
            } else {
                carr::forget($target, $segment);
            }
        } elseif (is_object($target)) {
            if ($segments && isset($target->{$segment})) {
                self::forget($target->{$segment}, $segments);
            } elseif (isset($target->{$segment})) {
                unset($target->{$segment});
            }
        }

        return $target;
    }

    /**
     * Fill in data where it's missing.
     *
     * @param mixed        $target
     * @param string|array $key
     * @param mixed        $value
     *
     * @return mixed
     */
    public static function fill(&$target, $key, $value)
    {
        return static::set($target, $key, $value, false);
    }

    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param array $array
     *
     * @return mixed
     */
    public static function head($array)
    {
        return reset($array);
    }

    /**
     * Get the last element from an array.
     *
     * @param array $array
     *
     * @return mixed
     */
    public static function last($array)
    {
        return end($array);
    }

    /**
     * Spaceship operator for php 5.6
     * 0 if $a == $b
     * -1 if $a < $b
     * 1 if $a > $b.
     *
     * @param mixed $a
     * @param mixed $b
     *
     * @return void
     */
    public static function spaceshipOperator($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        return $a > $b ? 1 : -1;
    }

    public static function dispatch($job)
    {
        return $job instanceof Closure
            ? new CQueue_PendingClosureDispatch(CQueue_CallQueuedClosure::create($job))
            : new CQueue_PendingDispatch($job);
    }

    public static function dispatchSync($job, $handler = null)
    {
        return CQueue::dispatcher()->dispatchSync($job, $handler);
    }

    public static function dispatchNow($job, $handler = null)
    {
        return CQueue::dispatcher()->dispatchNow($job, $handler);
    }

    /**
     * Determine whether the current environment is Windows based.
     *
     * @return bool
     */
    public static function windowsOs()
    {
        return PHP_OS_FAMILY === 'Windows';
    }

    /**
     * Transform the given value if it is present.
     *
     * @param mixed    $value
     * @param callable $callback
     * @param mixed    $default
     *
     * @return null|mixed
     */
    public static function transform($value, $callback, $default = null)
    {
        if (c::filled($value)) {
            if ($callback instanceof Closure) {
                return $callback($value);
            } else {
                return c::manager()->transform()->call($callback, $value);
            }
        }

        if (is_callable($default)) {
            return $default($value);
        }

        return $default;
    }

    /**
     * Replace a given pattern with each value in the array in sequentially.
     *
     * @param string $pattern
     * @param array  $replacements
     * @param string $subject
     *
     * @return string
     */
    public static function pregReplaceArray($pattern, array $replacements, $subject)
    {
        return preg_replace_callback($pattern, function () use (&$replacements) {
            foreach ($replacements as $value) {
                return array_shift($replacements);
            }
        }, $subject);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function trailingslashit($string)
    {
        return c::untrailingslashit($string) . '/';
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function untrailingslashit($string)
    {
        return rtrim($string, '/');
    }

    /**
     * Get theme data or theme object.
     *
     * @param null|string $key
     * @param null|mixed  $default
     *
     * @return CManager_Theme|mixed
     */
    public static function theme($key = null, $default = null)
    {
        if ($key !== null) {
            return static::manager()->theme()->getData($key, $default);
        }

        return static::manager()->theme();
    }

    /**
     * @return string
     */
    public static function locale()
    {
        return str_replace('_', '-', app()->getLocale());
        // return str_replace('_', '-', CF::getLocale());
    }

    /**
     * @param mixed $obj
     *
     * @return bool
     */
    public static function isIterable($obj)
    {
        return is_array($obj) || (is_object($obj) && ($obj instanceof \Traversable));
    }

    /**
     * @param string $type
     * @param string $message
     *
     * @return void
     */
    public static function msg($type, $message)
    {
        CApp_Message::add($type, $message);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function docRoot($path = null)
    {
        $docRoot = rtrim(DOCROOT, DS);
        if ($path != null) {
            if (is_string($path)) {
                $docRoot .= DS . trim($path, DS);
            }
        }

        return $docRoot . DS;
    }

    /**
     * @param null|string|array $path
     * @param null|string       $appCode
     *
     * @return null|string
     */
    public static function appRoot($path = null, $appCode = null)
    {
        if ($appCode == null) {
            $appCode = CF::appCode();
        }
        if ($appCode === null) {
            return null;
        }
        if (!in_array($appCode, CF::getAvailableAppCode())) {
            return null;
        }
        if (is_array($path)) {
            $path = implode(DS, $path);
        }
        $appRoot = c::untrailingslashit(static::docRoot('application/' . $appCode));
        if ($path != null) {
            if (is_string($path) && strlen($path) > 0) {
                $appRoot .= DS . trim($path, DS);
            }
        }

        return c::untrailingslashit($appRoot) . DS;
    }

    /**
     * @param null|string $name
     *
     * @return CStorage_Adapter
     */
    public static function disk($name = null)
    {
        return CStorage::instance()->disk($name);
    }

    /**
     * @param callable $callable
     *
     * @return Closure
     */
    public static function closureFromCallable($callable)
    {
        if (method_exists(Closure::class, 'fromCallable')) {
            return Closure::fromCallable($callable);
        }

        return function () use ($callable) {
            return call_user_func_array($callable, func_get_args());
        };
    }

    /**
     * @param null|mixed $event
     *
     * @return CBroadcast_PendingBroadcast
     */
    public static function broadcast($event = null)
    {
        return CBroadcast::manager()->event($event);
    }

    /**
     * @return string
     */
    public static function environment()
    {
        if (CF::isProduction()) {
            return 'production';
        }

        return CF::config('app.environment', 'development');
    }

    /**
     * Get an item from an object using "dot" notation.
     *
     * @param object      $object
     * @param null|string $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public static function objectGet($object, $key, $default = null)
    {
        if (is_null($key) || trim($key) === '') {
            return $object;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_object($object) || !isset($object->{$segment})) {
                return c::value($default);
            }

            $object = $object->{$segment};
        }

        return $object;
    }

    /**
     * Get Public Path.
     *
     * @param string $path
     *
     * @return string
     */
    public static function publicPath($path = null)
    {
        $publicPath = DOCROOT . 'public';
        if ($path != null && strlen($path) > 0) {
            $publicPath .= ltrim($path, '/');
        }

        return $publicPath;
    }

    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param  dynamic  key|key,default|data,expiration|null
     *
     * @throws \Exception
     *
     * @return \CCache_Manager|mixed
     */
    public static function cache()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return CCache::manager();
        }

        if (is_string($arguments[0])) {
            return CCache::manager()->store()->get(...$arguments);
        }

        if (!is_array($arguments[0])) {
            throw new Exception(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return CCache::manager()->store()->put(key($arguments[0]), reset($arguments[0]), isset($arguments[1]) ? $arguments[1] : null);
    }

    /**
     * Get CApp Formatter Instance.
     *
     * @return CApp_Formatter
     */
    public static function formatter()
    {
        return CApp_Formatter::instance();
    }

    /**
     * Get Schedule Instance.
     *
     * @return CCron_Schedule
     */
    public static function cron()
    {
        return CCron::schedule();
    }

    /**
     * Create a new cookie instance.
     *
     * @param null|string $name
     * @param null|string $value
     * @param int         $minutes
     * @param null|string $path
     * @param null|string $domain
     * @param null|bool   $secure
     * @param bool        $httpOnly
     * @param bool        $raw
     * @param null|string $sameSite
     *
     * @return \CHTTP_Cookie|\Symfony\Component\HttpFoundation\Cookie
     */
    public static function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = CHTTP::cookie();
        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }

    public static function setCookie($name, $value, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = CHTTP::cookie()->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);

        return CHTTP::cookie()->queue($cookie);
    }

    /**
     * @return CConsole_Kernel
     */
    public static function cli()
    {
        return CConsole::kernel();
    }

    /**
     * @return CCrypt_Encrypter
     */
    public static function crypt()
    {
        return CCrypt::encrypter();
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param CCollection $items
     * @param int         $total
     * @param int         $perPage
     * @param int         $currentPage
     * @param array       $options
     *
     * @return CPagination_LengthAwarePaginator
     */
    public static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return CContainer::getInstance()->makeWith(CPagination_LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }

    /**
     * Recursively diff two arrays.
     *
     * @param array $arrayOne
     * @param array $arrayTwo
     *
     * @return array
     */
    public static function arrayDiffAssocRecursive($arrayOne, $arrayTwo)
    {
        $difference = [];
        foreach ($arrayOne as $key => $value) {
            if (is_array($value) || $value instanceof CCollection) {
                if (!isset($arrayTwo[$key])) {
                    $difference[$key] = $value;
                } elseif (!(is_array($arrayTwo[$key]) || $arrayTwo[$key] instanceof CCollection)) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = c::arrayDiffAssocRecursive($value, $arrayTwo[$key]);
                    if ($new_diff != false) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!isset($arrayTwo[$key])) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }

    public static function json($data, $options = null, $depth = 512)
    {
        if ($options == null) {
            $options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
        }

        return json_encode($data, $options, $depth);
    }

    public static function jsonAttr($data, $options = null, $depth = 512)
    {
        return htmlspecialchars(c::json($data, $options, $depth), ENT_QUOTES, 'UTF-8');
    }

    public static function escAttr($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @return CApp_Contract_BaseInterface
     */
    public static function base()
    {
        return c::app()->base();
    }

    /**
     * @param null|string $group
     *
     * @return CApi_Manager
     */
    public static function api($group = null)
    {
        return CApi::manager($group);
    }

    /**
     * @param callable|Closure $callback
     *
     * @return callable|SerializableClosure
     */
    public static function toSerializableClosure($callback)
    {
        return $callback instanceof Closure ? new SerializableClosure($callback) : $callback;
    }

    /**
     * @param callable|Closure|SerializableClosure $callback
     *
     * @return callable|Closure
     */
    public static function toCallable($callback)
    {
        if ($callback instanceof SerializableClosure) {
            return $callback->getClosure();
        }

        return $callback;
    }

    /**
     * @param callable|Closure|SerializableClosure $callback
     *
     * @return bool
     */
    public static function isCallable($callback)
    {
        if ($callback instanceof SerializableClosure) {
            return true;
        }

        return is_callable($callback);
    }

    public static function isHtml($string)
    {
        return preg_match('/<[^<]+>/', $string, $m) != 0;
    }

    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param string $method
     *
     * @return \CBase_HtmlString
     */
    public static function methodField($method)
    {
        return new CBase_HtmlString('<input type="hidden" name="_method" value="' . $method . '">');
    }

    public static function faker($property = null)
    {
        $faker = FackerFactory::create();

        return $property ? $faker->{$property} : $faker;
    }

    public static function stopwatch($callback, $times = 1)
    {
        $totalTime = 0;

        foreach (range(1, $times) as $time) {
            $start = microtime(true);

            $callback();

            $totalTime += microtime(true) - $start;
        }

        return $totalTime / $times;
    }

    public static function swap(&$a, &$b)
    {
        $temp = $a;
        $a = $b;
        $b = $temp;
    }

    /**
     * @param null|string               $time
     * @param null|\DateTimeZone|string $tz
     *
     * @return CCarbon
     */
    public static function carbon($time = null, $tz = null)
    {
        return new CCarbon($time, $tz);
    }

    public static function call($callback, array $args = [])
    {
        if (is_string($callback)) {
            $className = null;
            $method = null;
            if (strpos($callback, '::') !== false) {
                list($className, $method) = explode('::', $callback);
            }
            if ($className == null && $method == null) {
                if (strpos($callback, '@') !== false) {
                    list($className, $method) = explode('@', $callback);
                }
            }
            if ($className == null && $method == null) {
                if (class_exists($callback)) {
                    $className = $callback;
                    $method == '__invoke';
                }
            }
            if ($className != null && $method !== null) {
                return call_user_func_array([$className, $method], $args);
            }
        }
        if (is_callable($callback)) {
            return call_user_func_array($callback, $args);
        }
        if ($callback instanceof \Opis\Closure\SerializableClosure) {
            return $callback->__invoke(...$args);
        }

        throw new Exception('callback is not callable');
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    public static function makePath(array $parts)
    {
        $path = implode(DIRECTORY_SEPARATOR, $parts);

        while (strpos($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) !== false) {
            $path = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
        }

        return $path;
    }

    /**
     * @param null|sting $locale
     *
     * @return array
     */
    public static function months($locale = null)
    {
        if ($locale == null) {
            $locale = CF::getLocale();
        }
        $format = function ($index) use ($locale) {
            if (class_exists(IntlDateFormatter::class)) {
                $formatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
                $formatter->setPattern('MMMM');

                return ucfirst($formatter->format(mktime(0, 0, 0, $index)));
            } else {
                return static::withLocale($locale, function () use ($index) {
                    return CCarbon::createFromTimestamp(mktime(0, 0, 0, $index, 1))->isoFormat('MMMM');
                });
            }
        };

        return array_combine(
            range(1, 12),
            array_map(function ($index) use ($format) {
                return $format($index);
            }, range(1, 12))
        );
    }

    /**
     * Run the callback with the given locale.
     *
     * @param string   $locale
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function withLocale($locale, $callback)
    {
        if (!$locale) {
            return $callback();
        }

        $original = CF::getLocale();

        try {
            CF::setLocale($locale);

            return $callback();
        } finally {
            CF::setLocale($original);
        }
    }

    /**
     * @return CAuth_Access_Gate
     */
    public static function gate()
    {
        return CAuth_Access_Gate::instance();
    }

    public static function resolveUserTimezone(CHTTP_Request $request)
    {
        return $request->timezone;
    }

    public static function queueable(Closure $closure)
    {
        return new CEvent_QueuedClosure($closure);
    }

    /**
     * Backward compatibility like array_is_list on php 8.1.
     *
     * @param array $array
     *
     * @return bool
     */
    public static function arrayIsList(array $array): bool
    {
        if ([] === $array || $array === array_values($array)) {
            return true;
        }

        $nextKey = -1;

        foreach ($array as $k => $v) {
            if ($k !== ++$nextKey) {
                return false;
            }
        }

        return true;
    }

    /**
     * @template T
     *
     * @param (callable(): T) $callback
     *
     * @return T
     */
    public static function once(callable $callback)
    {
        $trace = debug_backtrace(
            DEBUG_BACKTRACE_PROVIDE_OBJECT,
            2
        );

        $backtrace = new CBase_Once_Backtrace($trace);

        if ($backtrace->getFunctionName() === 'eval') {
            return call_user_func($callback);
        }

        $object = $backtrace->getObject();

        $hash = $backtrace->getHash();

        $cache = CBase_Once_Cache::instance();

        if (is_string($object)) {
            $object = $cache;
        }

        if (!$cache->isEnabled()) {
            return call_user_func($callback, $backtrace->getArguments());
        }

        if (!$cache->has($object, $hash)) {
            $result = call_user_func($callback, $backtrace->getArguments());

            $cache->set($object, $hash, $result);
        }

        return $cache->get($object, $hash);
    }

    public static function clsx()
    {
        $args = func_get_args();

        return CBase_CClsx::clsx(...$args);
    }

    /**
     * Generate CSS style string from an associative array.
     *
     * @param array $styles Styles array
     *
     * @return string
     */
    public static function stylex($styles)
    {
        if (!is_array($styles) || empty($styles)) {
            return '';
        }

        if (array_values($styles) === $styles) { // Check if it's an indexed array
            return implode(';', $styles) . ';';
        }

        $styleNames = '';
        foreach ($styles as $key => $value) {
            $cssPropertyName = cstr::kebabCase($key);
            if (is_string($value)) {
                $styleNames .= "{$cssPropertyName}:{$value};";

                continue;
            }

            if (is_bool($value)) {
                $styleNames .= $cssPropertyName;

                continue;
            }

            if (!is_array($value) || empty($value)) {
                continue;
            }

            foreach ($value as $condValue => $condition) {
                if ((is_callable($condition) && $condition()) || $condition) {
                    $styleNames .= "{$cssPropertyName}:{$condValue};";

                    break;
                }
            }
        }

        return $styleNames;
    }

    public static function elapsed()
    {
        return microtime(true) - CF_START;
    }

    /**
     * Join the given paths together.
     *
     * @param null|string $basePath
     * @param string      ...$paths
     *
     * @return string
     */
    public static function joinPaths($basePath, ...$paths)
    {
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath . implode('', $paths);
    }

    /**
     * @return CApp_Visitor
     */
    public static function visitor()
    {
        return c::app()->visitor();
    }
}

// End c
