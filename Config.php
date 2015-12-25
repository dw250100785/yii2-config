<?php
namespace weyii\config;

use yii\base\Component;
use weyii\base\helpers\Arr;
use yii\base\InvalidConfigException;

/**
 * Class Config
 * @package callmez\config
 */
class Config extends Component implements \ArrayAccess
{
    /**
     * All of the configuration data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        if (array_key_exists('data', $config)) {
            if (!is_array($config['data'])) {
                throw new InvalidConfigException('The "data" property must be array.');
            }

            $this->data = $config['data'];
            unset($config['data']);
        }

        parent::__construct($config);
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return Arr::has($this->data, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                static::set($innerKey, $innerValue);
            }
        } else {
            Arr::set($this->data, $key, $value);
        }
    }

    /**
     * Delete configuration value.
     *
     * @param  array|string  $key
     */
    public function delete($key)
    {
        Arr::forget($this->data, $key);
    }

    /**
     * Prepend a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function prepend($key, $value)
    {
        $array = $this->get($key);
        array_unshift($array, $value);
        $this->set($key, $array);
    }

    /**
     * Push a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function push($key, $value)
    {
        $array = $this->get($key);
        $array[] = $value;
        $this->set($key, $array);
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Returns whether there is a config entry with a specified key.
     * This method is required by the interface [[\ArrayAccess]].
     * @param string $key a key identifying the stored value
     * @return boolean
     */
    public function offsetExists($key)
    {
        return $this->get($key) !== false;
    }

    /**
     * Retrieves the value from config with a specified key.
     * This method is required by the interface [[\ArrayAccess]].
     * @param string $key a key identifying the stored value
     * @return mixed the value stored in config, false if the value is not in the config.
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Stores the value identified by a key into cache.
     * If the config already contains such a key, the existing value will be
     * replaced with the new ones. To add expiration and dependencies, use the [[set()]] method.
     * This method is required by the interface [[\ArrayAccess]].
     * @param string $key the key identifying the value to be stored
     * @param mixed $value the value to be stored
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Deletes the value with the specified key from config
     * This method is required by the interface [[\ArrayAccess]].
     * @param string $key the key of the value to be deleted
     */
    public function offsetUnset($key)
    {
        $this->delete($key);
    }

    /**
     * Get the root configuration key.
     *
     * @param $key
     * @return string
     */
    protected function getRootKey($key)
    {
        return explode('.', $key)[0];
    }
}