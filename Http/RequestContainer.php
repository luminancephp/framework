<?php
/**
 * @file Luminance/Http/RequestContainer.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * This is the Request container, this represents all $_POST data,
 * we have individual containers for each data type we want to
 * accept and handle. You can create custom containers by
 * extending this RequestContainer class for POST data.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class RequestContainer extends ParameterContainer
{
    /**
     * RequestContainer constructor.
     *
     * @param array $parameters The parameters to use, defaults to $_POST
     */
    public function __construct(array $parameters = array())
    {
        if(empty($parameters))
        {
            $parameters = $_POST;
        }
        parent::__construct($parameters);
    }
}