<?php
/**
 * @file Luminance/Form/Builder.php
 * @namespace Luminance\Form
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Form;

/**
 * This is a simple library to build forms, and exposes
 * helper methods like input, textarea, and other
 * form elements. Elements exposed can be
 * iterated on with arrays, and return
 * raw valid HTML, and will pass the
 * XML Validator class included in
 * \Luminance\Form\Validator
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Builder
{
    public static function generateTextarea(array $properties = array())
    {
        $doc = new \DOMDocument();
        $textarea = $doc->createElement('textarea');
        foreach($properties as $property => $value)
        {
            $attr = $doc->createAttribute($property);
            $attr->value = $value;
            $textarea->appendChild($attr);
        }
        return $doc->saveHTML();
    }

    /**
     * Generates a simple input field
     *
     * @param array $properties
     *
     * @return string
     */
    public static function generateInput(array $properties = array())
    {
        $doc = new \DOMDocument();
        $input = $doc->createElement('input');
        foreach($properties as $property => $value)
        {
            $attr = $doc->createAttribute($property);
            $attr->value = $value;
            $input->appendChild($attr);
        }
        return $doc->saveHTML();
    }

    /**
     * Generate a select with options, returns the raw
     * html to the requester object.
     *
     * @param array $properties
     * @param array $children
     *
     * @return string
     */
    public static function generateSelect(array $properties = array(), array $children = array())
    {
        $doc = new \DOMDocument();
        $select = $doc->createElement('select');
        foreach($properties as $property => $value)
        {
            $attr = $doc->createAttribute($property);
            $attr->value = $value;
            $select->appendChild($attr);
        }
        self::generateOptions($select, $children);
        return $doc->saveHTML();
    }

    /**
     * Generates a list of options
     *
     * @param \DOMElement $select_element
     * @param array $children
     */
    public static function generateOptions(&$select_element, array $children = array())
    {
        $doc = new \DOMDocument();
        foreach($children as $child)
        {
            // $child = array
            $local_child = $doc->createElement('option');
            foreach($child as $key => $value)
            {
                $attr = $doc->createAttribute($key);
                $attr->value = $value;
                $local_child->appendChild($attr);
                $select_element->appendChild($local_child);
            }
        }
    }
}