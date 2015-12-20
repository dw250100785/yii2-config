<?php
namespace callmez\config;

use yii\base\Component;
use weyii\base\helpers\ArrayHelper;

/**
 * Class Config
 * @package callmez\config
 */
abstract class Config extends Component implements \ArrayAccess
{
    /**
     * All of the configuration data.
     *
     * @var array
     */
    protected $data = [];

    public function init()
    {
        $this->loadData();
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return ArrayHelper::has($this->data, $key);
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
        return ArrayHelper::get($this->data, $key, $default);
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
            ArrayHelper::set($this->data, $key, $value);

            $this->saveData($key);
        }
    }

    /**
     * Delete configuration value.
     *
     * @param  array|string  $key
     */
    public function delete($key)
    {
        ArrayHelper::forget($this->data, $key);

        $this->saveData($key);
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
     * Load configuration data
     */
    abstract protected function loadData();

    /**
     * Save data to storage
     * Note if the change value of given key is null then should delete data;
     *
     * @param $key
     *
     */
    abstract protected function saveData($key);
}