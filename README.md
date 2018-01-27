![Logo](https://i.imgur.com/oups7UB.png)

# Luminance

> Modern PHP Framework designed for high-performance web applications

Luminance is a modern PHP framework, designed with love using PHP 7.1, ready for use immediately out of the box.

## Features

- PSR4 Autoloader
- Modern Router (magic, pre-defined routes)
- Clean, documented code base
- ORM Database Table Structure
- Powerful HTTP Request and Response Library
- Compatible with PHP 7 and above
- Configuration System
- Caching System
- Built-in CSRF and Sanitizing of input fields
- Micro-template parser

## Installation

To install Luminance, ensure you have PHP 7.0 or above installed, and run:

```shell
git clone https://github.com/luminancephp/framework.git
```

Now, change into the folder, and open your text editor. Change the application and database configuration paths in app/Configuration folder

## App Folder Structure

All applications have a small and simple folder structure:

- Cache
- Configuration
- Controllers
- Models
- Views

### Cache

This is where all cache files written by Luminance\Cache\File.php

### Configuration

This is where all application configuration will be, you can reference it by calling the Configuration Loader

Example:

```php
$configuration = new \Luminance\Configuration\Loader("file_name");

echo $configuration->config["test_field_name"];
```

### Controllers

All controllers here are case sensitive, the controller name must match the file name.

Example controller:

```php
<?php
/**
 * This is a hello world controller, as a sample controller
 *
 * @author Your Name <yourname@youremail.com>
 * @namespace Luminance\Controllers
 * @license LICENSE
 * @version 1.0.0
 * @package MY APP NAME
 */

namespace Controllers;

use Luminance\Controllers\BaseController;

class ExampleWorld extends BaseController
{
    public function index()
    {
        echo "Hello, world!";
    }
}
```

BaseController exposes the Request and Response object, by calling, respectively:

```php
$this->request->{methods};

$this->response->{methods};
```


### Models

These models are designed to be database objects, models must extend Table class.

Example:

```php
<?php
/**
 * This is a test database  
 */
namespace Luminance\Database;

class Accounts extends Table
{
    /**
     * This is a sample method, that queries a sample data set for it's ID, and returns the PDO object
     */
    public function getUserById($user_id)
    {
        if(!is_numeric($user_id))
        {
            return null;
        }
        $this->setQueryString("SELECT * FROM accounts WHERE id = ?");
        $this->replacements = array(
            $user_id
        );
        return $this->execute();
    }
}
```

### Views

This is a standard views directory, you can use this as your base views directory, but do not have to use the built-in template parser. You can use any template parser, such as Twig, Smarty, etc.

## Security

We take security very seriously, if you find a security issue in our core, please report it by emailing michaeldoestech@gmail.com with the subject line "Luminance Security Issue Report"

We ask you give us time to respond, investigate, and fix the bug. Responsible disclosure policy of 90 days remains in effect from time of report to time of fix, if a fix is not completed within 90 days, we ask you allow up to an additional 30 days to complete the patch properly.