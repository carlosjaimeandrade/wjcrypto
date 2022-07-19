<?php

namespace Src\help;

class Json
{
    /**
     * convert array in json
     *
     * @param array $array
     * @param int $httpCode
     * @return json
     */
    public function response($array, $httpCode)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
            header("HTTP/1.1 200 OK");
            die();
        }
        http_response_code($httpCode);
        echo json_encode($array);
    }

    /**
     * get json request and convert in array
     *
     * @return array
     */
    public function request()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
