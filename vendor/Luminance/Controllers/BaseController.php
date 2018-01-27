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

class BaseController
{
    private $request;
    private $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }
}