<?php
/**
 * This is the Base Controller for all application controllers
 *
 * Exposes: Request (Session, File, Query, Server, Post), Response
 *
 * Note: Table is not exposed as you may not want to use a database table, or
 * use a different ORM
 */

namespace Luminance\Controllers;

use Luminance\Http\Request;
use Luminance\Http\Response;
use Luminance\Security\Csrf;
use Luminance\Security\Sanitizer;

class BaseController
{
    private $request;
    private $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->csrf = new Csrf();
        $this->sanitizer = new Sanitizer();
        /**
         * Automatically generate CSRF token
         */
        $this->csrf->generateToken();
    }
}