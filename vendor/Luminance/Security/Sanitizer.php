<?php
/**
 * @file Luminance/Security/Sanitizer.php
 * @namespace Luminance\Security
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Security;

/**
 * This provides the sanitizer library for all HTML and user-supplied input,
 * you can think of this as a wrapper around existing functionality,
 * we don't want to re-invent the wheel here and therefore we just
 * wrap the HTMLEntites, and other available functions, and can
 * parse arrays and objects as necessary.
 *
 * Note: If you need to convert an array to object, just cast it, and
 * likewise if you need to cast an object to array. See example
 * here: $arr = array(); $obj = (object) $arr;
 * or here: $obj = new stdClass; $arr = (array) $obj;
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Sanitizer
{
    /**
     * @var string|array
     */
    protected $input_content = "";

    /**
     * Accepts either a string, or array of strings
     *
     * @param $string
     * @param array $strings
     */
    public function __construct($string = "", array $strings = array())
    {
        if(!empty($string) && $string !== null)
        {
            return $this->sanitize($string);
        }
        if(!empty($array))
        {
            return $this->sanitizeArray($strings);
        }
    }

    /**
     * Sanitizes just the string
     *
     * @param $string
     *
     * @return string
     */
    protected function sanitize($string)
    {
        $this->input_content = $string;
        return htmlentities($string);
    }

    /**
     * Sanitizes an array of strings
     *
     * @param array $array_of_strings
     *
     * @return array
     */
    protected function sanitizeArray(array $array_of_strings)
    {
        $output = array();
        foreach($array_of_strings as $string)
        {
            $output[] = htmlentities($string);
        }
        return $output;
    }
}