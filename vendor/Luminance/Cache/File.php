<?php
/**
 * @file Luminance/Cache/File.php
 * @namespace Luminance\Cache
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Cache;

/**
 * This will expose a file based caching system, ideally
 * to be used with templates, or data you can safely
 * cache without needing to refresh often.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class File
{
    /**
     * @var string
     */
    private $cache_directory = "app/Cache";

    /**
     * @var string[]
     */
    private $cache_entries = array();

    /**
     * @var boolean
     */
    private $cache_enabled = true;

    /**
     * File Cache constructor.
     *
     * This will load in the cache directory, validate
     * the files in their directory, and return the
     * cache file for the specific session
     *
     * @param string $cache_directory Optional cache directory
     */
    public function __construct($cache_directory = "")
    {
        if(!empty($cache_directory))
        {
            $this->cache_directory = $cache_directory;
        }
    }

    /**
     * This will write a file into the cache
     *
     * @param mixed $cache_content
     * @param string $expiry
     * @param array $details
     */
    public function addToCache($cache_content, $expiry = "50000", array $details = array())
    {
        $details["expiry"] = $expiry;
        $this->writeCacheFile(mt_rand().$expiry.".cache", $cache_content, $details);
    }

    /**
     * This does the actual writing, whereas addToCache
     * just creates an array list of files
     *
     * @param string $file
     * @param string $content
     * @param array $details
     */
    protected function writeCacheFile($file, $content, $details)
    {
        $path = $this->cache_directory . "/" . $file;
        file_put_contents($path, $content);
        $_SESSION["cache"] = array(
            "details" => $details,
            "path" => $path
        );
    }

    /**
     * This gets the current cache from session storage,
     * and returns the raw object with path and
     * details
     */
    public function getCache()
    {
        return $_SESSION["cache"];
    }

    /**
     * This does the cache deletion, we want to
     * delete expired cache for the current
     * session, and when we deconstruct a
     * session we'll call this method
     * so we can clean up the
     * cache
     */
    public function deleteCacheFiles()
    {
        $list = $_SESSION["cache"];
        foreach($list["details"] as $detail => $value)
        {
            if(isset($_SESSION[$detail]))
            {
                unset($_SESSION[$detail]);
            }
        }
        $path = $list["path"];
        unlink($path);
    }
}