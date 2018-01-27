<?php
/**
 * @file Luminance/Http/Response.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * This is the Response class, here we expose basic methods to
 * render data back to the client, including a very basic
 * and lightweight template rendering engine, this is
 * isolated in \Luminance\Template\Parser class, and
 * is only available on call, not included by
 * default in any of the controllers or
 * classes except Request class
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Response
{
    private $content = null;

    private static $ENABLE_ARRAY_DEBUG = array();

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function send()
    {
        Response::write($this->content);
    }

    public static function write($content)
    {
        if(gettype($content) === "string")
        {
            echo $content;
        }
        else if(gettype($content) === "array")
        {
            if(self::$ENABLE_ARRAY_DEBUG)
            {
                var_dump($content);
            }
            else
            {
                echo "[Type=Array]";
            }
        }
        else
        {
            echo "[Type=".gettype($content)."]";
        }
    }
}