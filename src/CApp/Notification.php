<?php
namespace Cresenity\Laravel\CApp;

use Cresenity\Laravel\CApp\Notification\ServiceWorkerService;
use Cresenity\Laravel\CF;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @see Cresenity\Laravel\CApp
 */
class Notification {
    protected $enabled = false;

    protected $debug = false;

    protected $driver;

    protected $config;

    protected $options;

    protected $startUrl;

    protected $sendTokenPath;

    protected $tokenLocalStorageKey;

    protected $group;

    private static $instance;

    public static function instance() {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct() {
        $this->group = null;
        $this->config = CF::config('cresenity.notification.web');
        $this->debug = Arr::get($this->config, 'debug', false);
        $this->driver = Arr::get($this->config, 'driver');
        $this->startUrl = Arr::get($this->config, 'start_url', '');
        $this->sendTokenPath = Arr::get($this->config, 'sendTokenPath', 'notification/token');
        $this->tokenLocalStorageKey = Arr::get($this->config, 'tokenLocalStorageKey', 'cres-' . $this->driver . '-token' . ($this->group ? '-' . $this->group : ''));
        $options = Arr::get($this->config, 'options', []);
        if (is_string($options)) {
            $options = json_decode($options, true);
        }
        $this->options = $options;
    }

    public function enable($group = null) {
        if (!$this->enabled) {
            $this->group = $group;
            $path = \c::request()->path();
            if ($this->group) {
                $groupConfig = CF::config('notification.web.groups.' . $this->group);
                $this->config = array_merge($this->config, $groupConfig);
                $this->debug = Arr::get($this->config, 'debug', false);
                $this->driver = Arr::get($this->config, 'driver');
                $this->startUrl = Arr::get($this->config, 'start_url', '');
                $this->sendTokenPath = Arr::get($this->config, 'sendTokenPath', 'notification/token');
                $this->tokenLocalStorageKey = Arr::get($this->config, 'tokenLocalStorageKey', 'cres-' . $this->driver . '-token' . ($this->group ? '-' . $this->group : ''));
            }
            if (isset($this->config['groups'])) {
                unset($this->config['groups']);
            }
            if (Str::startsWith($path, trim($this->startUrl, '/'))) {
                \c::router()->get($this->serviceWorkerUrl(), function () {
                    //disable debug bar here
                    if (CDebug::bar()->isEnabled()) {
                        CDebug::bar()->disable();
                    }
                    $options = [
                        'driver' => $this->driver,
                        'options' => $this->options,

                    ];
                    $output = (new ServiceWorkerService())->generate($options);

                    return c::response($output, 200, [
                        'Content-Type' => 'text/javascript',
                    ]);
                });
                $this->enabled = true;
            }
        }
    }

    /**
     * @return string
     */
    public function serviceWorkerUrl() {
        if ($this->driver == 'firebase') {
            return $this->startUrl . 'firebase-messaging-sw.js';
        }

        return $this->startUrl . 'cresenity-messaging-sw.js';
    }

    /**
     * @return string
     */
    public function serviceWorkerScope() {
        return $this->startUrl;
    }

    /**
     * @return string
     */
    public function getSendTokenUrl() {
        return $this->startUrl . $this->sendTokenPath;
    }

    /**
     * @return string
     */
    public function getTokenLocalStorageKey() {
        return $this->tokenLocalStorageKey;
    }

    public function getDriver() {
        return $this->driver;
    }

    public function getOptions() {
        return $this->options;
    }

    /**
     * @return bool
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function isDebug() {
        return $this->debug;
    }
}
