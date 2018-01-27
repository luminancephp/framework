<?php
/**
 * @file Luminance/Configuration/Loader.php
 * @namespace Luminance\Configuration
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Configuration;

/**
 * This class will find and load the configuration file requested by a name,
 * does not resolve by file path
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Loader
{
    /**
     * @var string
     */
    private $file_name;

    /**
     * @var array|null
     */
    public $config;

    protected function getConfig()
    {
        if(!empty($this->file_name))
        {
            if(file_exists("app/Configuration/".$this->file_name.".php"))
            {
                $this->config = include_once("app/Configuration/".$this->file_name.".php");
            }
        }
    }

    protected function castAsObject()
    {
        $this->config = (object) $this->config;
    }

    /**
     * If you supply a file name, we will automatically load the configuration
     * file into the $config object. If you flag as_object, it will cast
     * the array into an object, but will only convert the first level,
     * if you need to convert from there, it can be done with
     * json_decode(json_encode($array));
     *
     * @param string $file_name
     * @param boolean $as_object
     */
    public function __construct(string $file_name = "", bool $as_object = false)
    {
        if(isset($file_name) && !empty($file_name))
        {
            $this->file_name = $file_name;
            $this->getConfig();
            if($as_object)
            {
                $this->castAsObject();
            }
        }
    }

    /**
     * Loads a configuration file
     *
     * @param $config_name string
     */
    public function load(string $config_name)
    {
        $this->file_name = $config_name;
        $this->getConfig();
    }
}