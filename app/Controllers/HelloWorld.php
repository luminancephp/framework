<?php
/**
 * This is a hello world controller, as a sample controller
 *
 * @author Michael <michaeldoestech@gmail.com>
 * @namespace Luminance\Controllers
 * @license MIT
 * @version 1.0.0
 * @package Luminance
 */

namespace Controllers;

use Luminance\Controllers\BaseController;

class HelloWorld extends BaseController
{
    public function index()
    {
        echo "Hello, world!";
    }
}