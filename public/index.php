<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Content-type: application/json");

require "../vendor/autoload.php";

use Src\help\Routes;
use Src\help\Middleware;

$middleware = new Middleware(new Routes());
$middleware->pages = [
    '\App\Controllers\Produto\Index'
];
$middleware->check();

