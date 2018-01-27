<?php
/**
 * @file Luminance/Http/Session.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * This is the Session container, this represents all $_SESSION
 * data, we have individual containers for each data type we
 * want to accept and handle. You can create custom
 * containers by extending this SessionContainer
 * class for SESSION data.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class SessionContainer extends ParameterContainer
{
    /**
     * SessionContainer constructor.
     *
     * @param array $parameters The parameters to use, defaults to $_SESSION
     */
    public function __construct(array $parameters = array())
    {
        if(empty($parameters))
        {
            $parameters = $_SESSION;
        }
        parent::__construct($parameters);
    }

    /**
     * Overrides an existing session variable, or sets it
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        $this->parameters[$key] = $value;
    }

    /**
     * Check if we have an existing session
     *
     * @return boolean
     */
    public function hasSession()
    {
        return empty($this->parameters);
    }
}