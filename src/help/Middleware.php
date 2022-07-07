<?php

namespace Src\help;

use Src\help\Routes;
use Src\help\Json;

class Middleware{

    /**
     * @var array
     */
    public $pages;

    /**
     * @var Routes
     */
    private $routes;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Routes $routes
     */
    public function __construct(Routes $routes, Json $json){
        $this->routes = $routes;
        $this->json = $json;
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
                $this->json->response(['error' => "Access denied."], 401);
                exit();
            }   
        }

        $this->routes->render();
    }
}