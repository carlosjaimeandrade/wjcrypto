<?php

namespace Src\help;

use Src\help\Routes;
use Src\help\Json;
use Src\help\Request;

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
     * @var Request
     */
    private $request;

    /**
     * @param Routes $routes
     */
    public function __construct(Routes $routes, Json $json, Request $request){
        $this->routes = $routes;
        $this->json = $json;
        $this->request = $request;
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
                if(!$this->request->authorization()){
                    $this->json->response(['error' => "Access denied."], 401);
                    exit();
                }
            }   
        }

        $this->routes->render();
    }
}