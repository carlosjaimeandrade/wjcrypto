<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Content-type: application/json");

require "../vendor/autoload.php";

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(true);
$container = $containerBuilder->build();

$middleware = $container->get('Src\help\Middleware');

$middleware->pages = [
    ''
];

$middleware->check();

