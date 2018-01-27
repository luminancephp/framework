<?php
session_start();
/**
 * Luminance Framework
 * 
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 * @author Michael <michaeldoestech@gmail.com>
 * @package Luminance
 */

spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/vendor/';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if(file_exists($file))
    {
        require_once($file);
    }
    else
    {
        $alt_base_dir = __DIR__ . '/app/';
        $file = $alt_base_dir . str_replace('\\', '/', $class) . '.php';
        if(file_exists($file))
        {
            require_once($file);
        }
    }
});

$request_uri = $_SERVER['REQUEST_URI'];
$app_config = new \Luminance\Configuration\Loader("application");

if($request_uri === "/")
{
    if(isset($app_config->config["default_route"]))
    {
        $default_route = $app_config->config["default_route"];
        $splitter = explode("/", $default_route);
        $hier_prefix = "Controllers\\$splitter[0]";
        if(class_exists($hier_prefix))
        {
            $controller = new $hier_prefix;
            if(method_exists($controller, $splitter[1]))
            {
                $part = $splitter[1];
                $controller->$part();
            }
            else
            {
                $errorPage = new Luminance\Controllers\Error;
                $errorPage->file_not_found();
                return;
            }
        }
        else
        {
            $errorPage = new Luminance\Controllers\Error;
            $errorPage->file_not_found();
            return;
        }
    }
    else
    {
        $errorPage = new Luminance\Controllers\Error;
        $errorPage->file_not_found();
        return;
    }
}
else if(isset($app_config->config["routes"][$request_uri]))
{
    $index = $app_config->config["routes"][$request_uri];
    $controller = $index["controller"];
    $controller_arr = explode("/", $controller);
    $hier = "Controllers\\".$controller_arr[0];
    if(class_exists($hier))
    {
        $controller = new $hier();
        if(method_exists($controller, $controller_arr[1]))
        {
            $part = $controller_arr[1];
            $controller->$part();
        }
        else
        {
            $errorPage = new Luminance\Controllers\Error;
            $errorPage->file_not_found();
            return;
        }
    }
    else
    {
        $errorPage = new Luminance\Controllers\Error;
        $errorPage->file_not_found();
        return;
    }
}
else
{
    $parts = explode("/", $request_uri);
    $hier_prefix = "Controllers\\$parts[1]";
    if(class_exists($hier_prefix))
    {
        $controller = new $hier_prefix;
        if(method_exists($controller, $parts[2]))
        {
            $part = $parts[2];
            $controller->$part();
        }
        else
        {
            if(method_exists($controller, "default"))
            {
                $controller->default();
            }
            else
            {
                $errorPage = new Luminance\Controllers\Error;
                $errorPage->file_not_found();
            }
        }
    }
    else
    {
        $errorPage = new Luminance\Controllers\Error;
        $errorPage->file_not_found();
    }
}