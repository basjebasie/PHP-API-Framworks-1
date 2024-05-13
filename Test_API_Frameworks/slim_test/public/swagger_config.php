<?php

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    require_once $file;
});

use Zircote\Swagger\Swagger;

$swagger = new Swagger(new Swagger\Annotations());
$swagger->setBasePath('http://localhost');
$swagger->setSchemes(['http']);

require_once __DIR__ . '/../vendor/autoload.php';