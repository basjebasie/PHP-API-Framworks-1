<?php
require_once 'vendor/autoload.php';

$openapi = \OpenApi\scan('public/index.php');
header('Content-Type: application/json');
echo $openapi->toJson();