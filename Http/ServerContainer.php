<?php
/**
 * @file Luminance/Http/ServerContainer.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * This is the Server container, this represents all $_SERVER data,
 * we have individual containers for each data type we want to
 * accept and handle. You can create custom containers by
 * extending this ServerContainer class for SERVER data.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class ServerContainer extends ParameterContainer
{
    /**
     * ServerContainer constructor.
     *
     * @param array $parameters The parameters to use, defaults to $_POST
     */
    public function __construct(array $parameters = array())
    {
        if(empty($parameters))
        {
            $parameters = $_SERVER;
        }
        parent::__construct($parameters);
    }

    /**
     * Gets the basic auth information from $_SERVER
     *
     * @return array
     */
    public function getAuth()
    {
        return array(
            "user" => $this->parameters["PHP_AUTH_USER"],
            "pass" => $this->parameters["PHP_AUTH_PW"]
        );
    }
}