<?php
/**
 * @file Luminance/Http/ParameterContainer.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * All parameters will be in a container, this includes Files, Session, POST, GET data,
 * Server variables are container in thier own ServerContainer.php class,
 * therefore you must access $request->server to expose them to your
 * class or controller
 * 
 * @author Michael <michaeldoestech@gmail.com>
 */
class ParameterContainer
{
    /**
     * Parameters collected
     */
    protected $parameters;

    /**
     * Protected local variables
     */
    protected static $PROTECTED_LOCAL_VARIABLES = array("parameters", "all", "keys", "replace", "set", "get", "count", "getIterator", "setObjects", "unsetGlobals");

    /**
     * @param array $parameters An array of request parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns the parameters.
     * 
     * @return array An array of parameters
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * Returns the keys of the parameters
     *
     * @return array $parameters
     */
    public function keys()
    {
        return array_keys($this->parameters);
    }

    /**
     * Replace the current parameters in storage
     *
     * @param array $parameters
     */
    public function replace(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * Add parameters to the parameter storage
     *
     * @param array $parameters Add items to the parameters
     */
    public function add(array $parameters = array())
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    /**
     * Returns a parameter inside the parameters array, determined by it's key.
     * We will return a default value if one is set, or null if none is set.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function get(string $key, string $default)
    {
        if(array_key_exists($key, $this->parameters))
        {
            return $this->parameters[$key];
        }
        return $default;
    }

    /**
     * Sets a parameter by it's key
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Removes a parameter by it's key
     *
     * @param string $key
     */
    public function remove(string $key)
    {
        unset($this->parameters[$key]);
    }

    /**
     * Returns an iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    /**
     * Returns the number of entries in the parameters
     *
     * @return int
     */
    public function count()
    {
        return count($this->parameters);
    }

    /**
     * Unset the globals specified
     */
    public function unsetGlobals()
    {
        unset($_GET);
        unset($_POST);
    }

    /**
     * Creates an ORM-style object for dynamic accessing variables
     */
    public function setObjects()
    {
        foreach($this->parameters as $key => $value)
        {
            if(!in_array($key, self::$PROTECTED_LOCAL_VARIABLES))
            {
                $this->$key = $value;
            }
        }
    }
}