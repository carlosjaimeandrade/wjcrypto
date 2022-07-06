<?php

namespace Src\help;

class Middleware{

    public $routes;
    
    public function check($page){
        foreach($this->routes as $route){
            if($route == $page){
                echo "não autorizado";
                exit();
            }   
        }
    }

}