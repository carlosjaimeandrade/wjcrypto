<?php

namespace Src\help;

use Src\help\Routes;

class Middleware{

    /**
     * @var array
     */
    public $pages;

    /**
     * @var Routes
     */
    public $routes;

    /**
     * @param Routes $routes
     */
    public function __construct(Routes $routes){
        $this->routes = $routes;
    }
    
    /**
     * check page autorization
     * 
     * @param array
     * @return void
     */
    public function check(){
        foreach($this->pages as $page){
            if($page == $this->routes->getPage()){
                echo "não autorizado";
                exit();
            }   
        }

        $this->routes->render();
    }

}