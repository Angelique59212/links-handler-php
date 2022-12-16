<?php
use App\Router;
use Symfony\Component\ErrorHandler\Debug;

require_once '../vendor/autoload.php';
require '../Router.php';
require '../src/Model/Connect.php';
require '../Config.php';

session_start();

Debug::enable();

try {
    Router::route();
}
catch (ReflectionException $e) {
    echo "Une erreur est survenue";
}
