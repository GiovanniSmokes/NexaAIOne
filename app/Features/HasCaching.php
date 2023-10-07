<?php
namespace App\Features;

use Illuminate\Support\Facades\Cache;

trait HasCaching
{
    /**
     * An array that defines the options for caching.
     *
     * @var array
     */
    protected static $cachingOptions = [
        'cachingPeriod' => [
            "name" => "cachingPeriod",
            "type" => "number",
            "required" => false,
            "desc" => "How long should a response be cached, in minutes? If the same request is submitted again, the response will be retrieved from the cache if available. Set to 0 to disable caching.",
            "default" => 0,
            "isApiOption" => true,
            "_group" => 'Caching',
        ],
        'cacheScope' => [
            "name" => "cacheScope",
            "type" => "select",
            "required" => false,
            "desc" => "Is the caching scope set per session, or is it global (across all sessions)? set 'session' for individual session-based caching or 'global' for caching that spans across all sessions.",
            "default" => "session",
            "isApiOption" => false,
            "options"=>[
                'session' => 'Per Session',
                'global' => 'Global'
            ],
            "_group" => 'Caching',
        ],
        'clearCache' => [
            "name" => "clearCache",
            "type" => "boolean",
            "required" => false,
            "desc" => "Clear cache for the specified request.",
            "isApiOption" => true,
            'default' => 0,
            "_group" => 'Caching',
        ],
        'session' => [
            "name" => "session",
            "type" => "string",
            "required" => false,
            "desc" => "Unique session id for this request.",
            "default" => "global",
            "isApiOption" => true,
            "_group" => 'General',
        ],
    ];

    /**
     * The cache key for the message response.
     *
     * @var string
     */
    protected $cacheKey = 'unknown';

    /**
     * A boolean that indicates whether the cache has been initialized.
     *
     * @var bool
     */
    protected $cacheInit = false;

    /**
     * Initializes the cache key based on the provided options.
     *
     * If `cachingPeriod` is not set in options, it returns false.
     * The cache key is generated by hashing the message option and
     * optionally prefixing with the session value if the cacheScope is set to 'session'.
     *
     * @access private
     * @return bool Returns true if cache key is generated, otherwise false.
     */
    private function __cacheInit($optionsForCachingKey = [], $extraString = null)
    {
        if (!isset($this->options['cachingPeriod']) || $this->options['cachingPeriod'] == 0) {
            return false;
        }
        if($this->cacheInit) {
            return true;
        }
        $this->cacheInit = true;

        $requestCachingKeyOptionName = null;

        foreach($optionsForCachingKey as $key){
            if(isset($this->options[$key])) {
                $requestCachingKeyOptionName .= ':' . (string)$this->options[$key];
            }
        }
        $mdKey = md5($requestCachingKeyOptionName . $extraString);

        if($this->options['cacheScope'] == 'session') {
            $this->cacheKey = 'msg:' . $this->api_id. ':'. $this->options['session'] . ':' .  $mdKey;
        } else {
            $this->cacheKey = 'msg:' . $this->api_id. ':'. $mdKey;
        }

        $this->debug('__cacheInit()', $this->cacheKey);
        return true;
    }

    /**
     * Clears the cache for the specific key stored in `$this->cacheKey`.
     *
     * @access private
     * @return void
     */
    private function clearCache()
    {
        if(!$this->cacheInit) {
            return false;
        }
        $this->debug('clearCache()', true);
        Cache::forget($this->cacheKey);
        return true;
    }

    /**
     * Retrieves the cached value associated with the cache key.
     *
     * If the cache has a value, it retrieves the value, resets its expiration,
     * and then returns the cached value. If no cached value is found,
     * the provided default value is returned.
     *
     * @access private
     * @param mixed $default The default value to return if cache entry is not found.
     * @return mixed Returns cached value or the provided default.
     */
    private function getCache($default = false)
    {
        if(!$this->cacheInit) {
            return false;
        }
        if(Cache::has($this->cacheKey)) {
            $value = Cache::get($this->cacheKey);
            $seconds = $this->options['cachingPeriod'] * 60;
            Cache::put($this->cacheKey, $value, $seconds);
            $this->debug('getCache()', $value);
            return $value;
        } else {
            $this->debug('getCache()', null);
        }
        return $default;
    }

    /**
     * Sets a value to the cache using the initialized cache key.
     *
     * The cached value will expire based on the `cachingPeriod` option.
     *
     * @access private
     * @param mixed $value The value to be cached.
     * @return void
     */
    private function setCache($value)
    {
        if(!$this->cacheInit) {
            return false;
        }
        $this->debug('setCache()', $value);
        $seconds = $this->options['cachingPeriod'] * 60;
        Cache::put($this->cacheKey, $value, $seconds);
    }
}
