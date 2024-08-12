<?php
namespace Cresenity\Laravel;

class CCache
{
    /**
     * @var CCache_Repository[]
     */
    protected static $repository;

    /**
     * @var CCache_RateLimiter
     */
    protected static $rateLimiter;

    /**
     * @param null|mixed $name
     *
     * @deprecated since 1.2
     *
     * @return CCache_Repository
     */
    public static function repository($name = null)
    {
        $options = static::resolveConfig($name);
        $instanceKey = $name;
        if (!is_string($name)) {
            $instanceKey = carr::hash($options);
        }
        if (!is_array(self::$repository)) {
            self::$repository = [];
        }
        if (!isset(self::$repository[$instanceKey])) {
            self::$repository[$instanceKey] = new CCache_Repository($options);
        }

        return self::$repository[$instanceKey];
    }

    public static function resolveConfig($name)
    {
        $config = CConfig::instance('cache');
        $options = null;
        if (is_array($name)) {
            $options = $name;
        }
        if ($options == null) {
            if ($name = null) {
                $name = $config->get('default');
            }

            $options = $config->get($name);
        }

        $defaultOptions = [
            'driver' => 'array',
        ];

        return $options ? $options : $defaultOptions;
    }

    public static function store($name = null)
    {
        return CCache_Manager::instance()->store($name);
    }

    /**
     * Get CCache_Manager Instance.
     *
     * @return CCache_Manager
     */
    public static function manager()
    {
        return CCache_Manager::instance();
    }

    public static function rateLimiter()
    {
        if (static::$rateLimiter == null) {
            static::$rateLimiter = new CCache_RateLimiter(CCache::manager()->driver(CF::config('cache.limiter')));
        }

        return static::$rateLimiter;
    }
}
