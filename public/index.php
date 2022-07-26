<?php


require "../vendor/autoload.php";

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(true);
$container = $containerBuilder->build();

$middleware = $container->get('Src\help\Middleware');

$middleware->pages = [
    '\App\Controllers\User\Index' => ['get'],
    '\App\Controllers\Auth\Index' => ['get'], 
    '\App\Controllers\Deposit\Index' => ['post'],
    '\App\Controllers\Removal\Index' => ['post'],
    '\App\Controllers\Transfer\Index' => ['post'],
    '\App\Controllers\History\Index' => ['get']
];


$middleware->check();

