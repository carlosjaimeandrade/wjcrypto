<?php
require "../vendor/autoload.php";

use Src\help\Routes;
use Src\help\Middleware;

$routes = new Routes();
$middleware = new Middleware();
$middleware->routes = [
    '\App\Controllers\Produto\Index',
    '\App\Controllers\Home\Index'
];
$middleware->check($routes->getPage());
$routes->render();

