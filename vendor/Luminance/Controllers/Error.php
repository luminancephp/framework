<?php
/**
 * This is an error controller, it's the default error controller
 */

namespace Luminance\Controllers;

class Error
{
    private static $FILE_NOT_FOUND_ERROR_CODE = 404;

    public function file_not_found()
    {
        http_response_code(self::$FILE_NOT_FOUND_ERROR_CODE);
        exit("<h1>File Not Found</h1><p>The file you requested was not found.</p>");
    }
}