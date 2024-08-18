<?php
namespace Cresenity\Laravel\CAjax;

use c;
use Cresenity\Laravel\CAjax;
use Cresenity\Laravel\CAjax\Exception\AuthAjaxException;
use Cresenity\Laravel\CAjax\Exception\ExpiredAjaxException;
use Cresenity\Laravel\CCarbon;
use Cresenity\Laravel\CTemporary;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class Method implements Jsonable
{
    public $name = '';

    public $method = 'GET';

    /**
     * @var array
     */
    public $data = [];

    public $type = '';

    public $target = '';

    public $param = [];

    public $args = [];

    public $expiration;

    /**
     * @var bool|array
     */
    public $auth;

    public function __construct($options = [])
    {
        $this->auth = false;
        if ($options == null) {
            $options = [];
        }
        $this->fromArray($options);
    }

    /**
     * @param string $key
     * @param array  $data
     *
     * @return $this
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;

        return $this;
    }

    public function enableAuth()
    {
        $guard = \c::app()->auth()->guard();
        $auth = true;
        if ($guard) {
            $auth = [];
            $auth['guard'] = \c::app()->auth()->guardName();
        }
        if ($guard instanceof SessionGuard) {
            $auth['id'] = $guard->id();
        }
        $this->auth = $auth;

        return $this;
    }

    public function setExpiration($expiration)
    {
        $expiration = $expiration instanceof \DateTimeInterface
        ? $expiration->getTimestamp() : $expiration;
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        if (class_exists($type)) {
            $type = c::classBasename($type);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @param array $type
     *
     * @return $this
     */
    public function setArgs(array $args)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param int $jsonOption
     *
     * @return string
     */
    public function makeUrl($jsonOption = 0)
    {
        //generate ajax_method
        $json = $this->toJson($jsonOption);

        //save this object to file.

        $ajaxMethod = date('Ymd') . c::randmd5();
        $disk = CTemporary::disk();
        $filename = $ajaxMethod . '.tmp';

        $file = CTemporary::getPath('ajax', $filename);
        $disk->put($file, $json);

        $base_url = c::url('');

        return $base_url . 'cresenity/ajax/' . $ajaxMethod;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'method' => $this->method,
            'type' => $this->type,
            'target' => $this->target,
            'param' => $this->param,
            'args' => $this->args,
            'expiration' => $this->expiration,
            'auth' => $this->auth,
            'data' => $this->data,
        ];
    }

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param string $json
     *
     * @return $this
     */
    public function fromJson($json)
    {
        $jsonArray = json_decode($json, true);

        return $this->fromArray($jsonArray);
    }

    public function fromArray(array $array)
    {
        $this->data = Arr::get($array, 'data', []);
        $this->method = Arr::get($array, 'method', 'GET');
        $this->type = Arr::get($array, 'type');
        $this->name = Arr::get($array, 'name');
        $this->args = Arr::get($array, 'args');
        $this->target = Arr::get($array, 'target');
        $this->expiration = Arr::get($array, 'expiration');
        $this->auth = Arr::get($array, 'auth');

        return $this;
    }

    /**
     * @param string $json
     *
     * @return \Cresenity\Laravel\CAjax\Method
     */
    public static function createFromJson($json)
    {
        $instance = new Method();

        return $instance->fromJson($json);
    }

    /**
     * @param \Cresenity\Laravel\CAjax\Method $ajaxMethod
     * @param null|array   $input
     *
     * @throws CAjax_Exception
     *
     * @return \Cresenity\Laravel\CAjax\Engine
     */
    public static function createEngine(Method $ajaxMethod, $input = null)
    {
        $type = $ajaxMethod->type;
        if ($type == 'SearchSelect') {
            $type = CAjax::TYPE_SELECT_SEARCH;
        }
        $class = 'CAjax_Engine_' . $type;
        if (!class_exists($class)) {
            throw new CAjax_Exception(c::__('class ajax engine :class not found', [':class' => $class]));
        }
        $engine = new $class($ajaxMethod, $input);

        return $engine;
    }

    public function getExpiration()
    {
        return $this->expiration;
    }

    protected function checkAuth()
    {
        if ($this->auth) {
            $guard = null;
            if (is_array($this->auth)) {
                $guardName = Arr::get($this->auth, 'guard');
                $guard = \c::auth($guardName);
            }
            if ($guard->check()) {
                if (get_class($guard) == SessionGuard::class) {
                    if (Arr::get($this->auth, 'id') != $guard->id()) {
                        return false;
                    }
                }

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function executeEngine($input = null)
    {
        $expiration = $this->getExpiration();

        if ($expiration && CCarbon::now()->getTimestamp() > $expiration) {
            throw new ExpiredAjaxException('Expired Link');
        }

        if (!$this->checkAuth()) {
            throw new AuthAjaxException('Unauthenticated');
        }
        $engine = self::createEngine($this, $input);
        $response = $engine->execute();
        if ($response != null && $response instanceof JsonResponse) {
            return $response;
        }

        return $response;
    }
}
