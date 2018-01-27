<?php
/**
 * @file Luminance/Http/Request.php
 * @namespace Luminance\Http
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Http;

/**
 * Request represents an HTTP request sent by a client.
 * 
 * @author Michael <michaeldoestech@gmail.com>
 */
class Request
{
    const METHOD_HEAD       = 'HEAD';
    const METHOD_GET        = 'GET';
    const METHOD_POST       = 'POST';
    const METHOD_PUT        = 'PUT';
    const METHOD_PATCH      = 'PATCH';
    const METHOD_DELETE     = 'DELETE';
    const METHOD_PURGE      = 'PURGE';
    const METHOD_OPTIONS    = 'OPTIONS';
    const METHOD_TRACE      = 'TRACE';
    const METHOD_CONNECT    = 'CONNECT';

    /**
     * @var string[]
     */
    protected static $trustedProxies = array();

    /**
     * @var string[]
     */
    protected static $trustedHosts = array();

    /**
     * Request body parameters ($_POST)
     * 
     * @var \Luminance\Http\ParameterContainer
     */
    public $request;

    /**
     * Query string parameters ($_GET)
     * 
     * @var \Luminance\Http\ParameterContainer
     */
    public $query;

    /**
     * Server parameters ($_SERVER)
     * 
     * @var \Luminance\Http\ServerContainer
     */
    public $server;

    /**
     * Files that have been uploaded ($_FILES)
     * 
     * @var \Luminance\Http\FileContainer
     */
    public $files;

    /**
     * @var string|resource
     */
    protected $content;

    /**
     * @var string
     */
    protected $pathInfo;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \Luminance\Http\SessionContainer
     */
    protected $session;

    /**
     * @param array             $query      The query string ($_GET)
     * @param array             $request    The request data ($_POST)
     * @param array             $session    The session data ($_SESSION)
     */
    public function __construct(array $query = array(), array $request = array(), array $session = array())
    {
        $this->request = new RequestContainer($request);
        $this->query   = new QueryContainer($query);
        $this->session = new SessionContainer($session);
        $this->server  = new ServerContainer(array());
        $this->files   = new FileContainer(array());
        $this->session = new SessionContainer(array());
    }

    /**
     * Creates new request variable from PHP globals
     *
     * @return Request
     */
    public static function createFromGlobals()
    {
        $query = $_GET;
        $request = $_POST;
        $session = $_SESSION;
        return new Request($query, $request, $session);
    }

    /**
     * Gets a parameter from the query or request data
     *
     * Order of operations:
     * 1. GET
     * 2. POST
     * 3. SESSION
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function get($key, $default="")
    {
        if($this->request->get($key, $default))
        {
            return $this->request->get($key, $default);
        }

        if($this->query->get($key, $default))
        {
            return $this->query->get($key, $default);
        }

        if($this->session->get($key, $default))
        {
            return $this->session->get($key, $default);
        }

        return $default;
    }

    /**
     * Get the current client IP address
     *
     * @return array The client IP address, or list of IP addresses
     */
    public function getClientsIp()
    {
        $ip = $this->server->get('REMOTE_ADDR', "999.999.999.999");
        return array($ip);
    }

    /**
     * Returns the current script name
     *
     * @return string
     */
    public function getScriptName()
    {
        return $this->server->get('SCRIPT_NAME', '');
    }

    /**
     * Get the real request method sent by the client
     *
     * @reeturn string
     */
    public function getMethod()
    {
        return $this->server->get('REQUEST_METHOD', 'GET');
    }

    /**
     * Determine if the request is GET
     *
     * @return boolean
     */
    public function isGet()
    {
        return $this->method === "GET";
    }

    /**
     * Determine if the request is POST
     *
     * @return boolean
     */
    public function isPost()
    {
        return $this->method === "POST";
    }

    /**
     * Determine if the the request is made over AJAX
     *
     * @return boolean
     */
    public function isAjax()
    {
        return 'XMLHttpRequest' === $this->server->get('HTTP_X_REQUESTED_WITH', false);
    }
}