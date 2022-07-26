<?php
declare(strict_types=1);

namespace Src\help;

class Routes
{
    /**
     * clear the url for render
     *
     * @return array
     */
    public function clearUrl():array
    {
        $uri =
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $bar = substr($uri, -1);
        $base = explode("/", $uri);
        $base = array_values(array_filter($base));

        if(count( $base)>1){
            if ($bar == "/") {
                $uri = rtrim($uri, '/');
                header('Location:' . $uri);
                exit();
            }
        }

        $method = "index";

        if (count($base) >= 2) {
            $controller =  ucwords($base[0]) . "\\" . ucwords($base[1]);
            if(isset($base[2])){
                $method = $base[2];
            }
        } 


        if (count($base) == 1) {
            $controller = ucwords($base[0])  . "\\" . "Index";
            if(isset($base[1])){
                $method = $base[1];
            }
        }
        
        if (count($base) == 0) {
            $controller = "Home\Index";
            $method = 'index';
        }

        return ['controller' => $controller, 'method' => $method];
    }

    /**
     * renders the page
     *
     * @return void
     */
    public function render()
    {
        $route = $this->clearUrl();
        $controller = $route['controller'];
        $method = $route['method'];
        $class = "\\App\\Controllers\\" . $controller;
        
        if (class_exists($class)) {
            $containerBuilder = new \DI\ContainerBuilder();
            $containerBuilder->useAutowiring(true);
            $container = $containerBuilder->build();
            $active = $container->get($class);
            
        } else {
            http_response_code(404);
            echo json_encode(['error' => "Endpoint invalid"]);
            exit();
        }
        if (method_exists($active, $method)) {
            $active->$method();
        } else {
            http_response_code(404);
            echo json_encode(['error' => "Endpoint invalid"]);
        }
    }

    /**
     * return page 
     *
     * @return string
     */
    public function getPage():string{
        $route = $this->clearUrl();
        $controller = $route['controller'];
        return  "\\App\\Controllers\\" . $controller;
    }
}
