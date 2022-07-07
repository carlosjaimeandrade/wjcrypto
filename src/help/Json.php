<?php

namespace Src\help;

class Json{
    /**
     * convert array in json
     *
     * @param array $array
     * @param int $httpCode
     * @return json
     */
    public function response($array, $httpCode){
        http_response_code($httpCode);
        echo json_encode($array);
    }

    /**
     * get json request and convert in array
     *
     * @return array
     */
    public function request(){
        return json_decode(file_get_contents('php://input'), true);
    }
}