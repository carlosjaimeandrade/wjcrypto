<?php

namespace Src\config;

use App\Controllers\Produto\Get;

class Container
{

    public function create()
    {
        $containerBuilder = new \DI\ContainerBuilder();
        $containerBuilder->addDefinitions([
            Get::class => \DI\create(Get::class)
        ]);

        return $containerBuilder->build();
    }
}
