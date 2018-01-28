<?php
/**
 * @file Luminance/Http/FileContainer.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * Any of the submitted files will be available here,
 * we automatically set a blacklist of unacceptable
 * files, which you can tweak by extending this file
 * container, or by flagging them in the Request
 * object before calling any of the methods in
 * this class
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class FileContainer extends ParameterContainer
{
    /**
     * @var array
     */
    private static $PROTECTED_UNSAFE_FILE_EXTENSIONS = array("php", "php3", "php4", "php5", "phar");

    /**
     * @var string
     */
    private $upload_directory = "";

    /**
     * @var string
     */
    private static $MAX_FILE_SIZE_IN_BYTES = 500000;

    /**
     * @param array $parameters An array of HTTP files
     */
    public function __construct(array $parameters = array())
    {
        $this->replace($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function replace(array $files = array())
    {
        $this->parameters = array();
        $this->add($files);
    }

    /**
     * Get all the $_FILES array and pump it into parameters
     */
    public function getFiles()
    {
        $this->replace($_FILES);
    }

    /**
     * Check if the file extension is safe or not, as defined by a rules list
     *
     * @param string $file_index
     *
     * @return boolean
     */
    public function isFileExtensionSafe(string $file_index)
    {
        $file_name_tmp = basename($this->parameters[$file_index]["name"]);
        $image_file_type = strtolower(pathinfo($file_name_tmp, \PATHINFO_EXTENSION));
        if(in_array($image_file_type, self::$PROTECTED_UNSAFE_FILE_EXTENSIONS))
        {
            return false;
        }
        return true;
    }

    /**
     * Save a file by it's index, saves under a random name and sends back the name
     *
     * @param string $file_index
     *
     * @return mixed
     */
    public function save(string $file_index)
    {
        $target = $this->upload_directory . "/" . basename($this->parameters[$file_index]["name"]);
        if(file_exists($this->upload_directory . "/" . basename($this->parameters[$file_index]["name"])))
        {
            return false;
        }
        if($this->parameters[$file_index] > self::$MAX_FILE_SIZE_IN_BYTES)
        {
            return false;
        }
        if(move_uploaded_file($this->parameters[$file_index]["tmo_name"], $target))
        {
            return array(
                "file" => $target,
                "status" => 200
            );
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete a file from the parameters stack
     *
     * @param string $file_index
     *
     * @return boolean
     */
    public function delete(string $file_index)
    {
        if(isset($this->parameters[$file_index]))
        {
            unset($this->parameters[$file_index]);
            return true;
        }
        return false;
    }

    /**
     * Delete a previously saved file by it's path
     *
     * @param string $file_path
     *
     * @return boolean
     */
    public function deleteByPath(string $file_path)
    {
        if(file_exists($file_path))
        {
            unlink($file_path);
            return true;
        }
        return false;
    }
}