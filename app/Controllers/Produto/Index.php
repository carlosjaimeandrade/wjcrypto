<?php

namespace App\Controllers\Produto;

use App\Controllers\Produto\Get;

class Index
{
    /**
     * DI Get
     *
     * @var Get
     */
    public $get;

    /**
     * contruct for create controller
     *
     * @param Get $get
     */
    public function __construct(Get $get){
        $this->get = $get; 
    }

    /**
     * get http request 
     *
     * @return void
     */
    public function Index(){

        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
        
        if($httpMethod == "get"){
            $this->get->create();
        }

    }
}
