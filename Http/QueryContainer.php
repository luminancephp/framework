<?php
/**
 * @file Luminance/Http/QueryContainer.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * This is the Query container, this represents all $_GET data,
 * we have individual containers for each data type we want to
 * accept and handle. You can create custom containers by
 * extending this QueryContainer class for GET data.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class QueryContainer extends ParameterContainer
{
    /**
     * QueryContainer constructor.
     *
     * @param array $parameters The parameters to use, defaults to $_POST
     */
    public function __construct(array $parameters = array())
    {
        if(empty($parameters))
        {
            $parameters = $_GET;
        }
        parent::__construct($parameters);
    }
}