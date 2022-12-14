<?php

require_once '../vendor/autoload.php';
require '../Router.php';

use App\Router;

session_start();

try {
    Router::route();
}
catch (ReflectionException $e) {
    echo "Une erreur est survenue";
}
