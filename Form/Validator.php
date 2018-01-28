<?php
/**
 * @file Luminance/Form/Validator.php
 * @namespace Luminance\Form
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Form;

/**
 * A simple XML validator designed to be used with forms
 * and will return boolean of success or failure, and
 * if any errors occurs it will populate the
 * $xml_errors field with libxml_get_errors
 * and you can iterate on this list to
 * determine where the errors are
 * and what to fix in the XML
 * source
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Validator
{
    /**
     * @var string
     */
    private $xml_path = "";

    /**
     * @var string
     */
    private $raw_xml = "";

    /**
     * @var boolean
     */
    private $xml_valid = false;

    /**
     * @var array
     */
    private $xml_errors = array();

    /**
     * This function creates a new DOMDocument, and loads in the XML
     * and it will flag if valid, or invalid XML
     *
     * @return boolean
     */
    protected function validateXML()
    {
        $xml = $this->raw_xml;
        libxml_use_internal_errors(TRUE);
        $doc = simplexml_load_string($xml);
        if(!$doc)
        {
            $this->xml_errors = libxml_get_errors();
        }
        else
        {
            $this->xml_valid = true;
        }
    }

    /**
     * Pass a form's raw XML, or pass by path, and flag is_path = true
     * and this library will validate it is a valid XML document
     * and warn if there is any errors in the form XML
     *
     * @param string $xml_form_or_path
     * @param bool $is_path
     *
     * @return boolean
     */
    public function __construct(string $xml_form_or_path = "", bool $is_path = false)
    {
        if($is_path)
        {
            $this->xml_path = $xml_form_or_path;
            $this->raw_xml = file_get_contents($this->xml_path);
        }
        else
        {
            $this->raw_xml = $xml_form_or_path;
        }
        $this->validateXML();
        return $this->xml_valid;
    }

    /**
     * Deconstructor will clear xml_path, raw_xml, xml_valid variables
     */
    public function __destruct()
    {
        libxml_clear_errors();
        unset($this->xml_path);
        unset($this->raw_xml);
    }
}