<?php

namespace App\Controllers\Auth\Http;

use Firebase\JWT\JWT;
use Src\help\Json;

class Post{
    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     */
    public function __construct(Json $json){
        $this->json = $json;
    }

    /**
     * create method HTTP
     *
     * @return json
     */
    public function create(){
        $token = $this->token();
        $this->json->response(['token'=> $token], 200);
    }

    /**
     * return token
     *
     * @return string
     */
    private function token():string{
        $key = 'Aswd212$$@#as@ad2f58456s485a4as984d872';
        $payload = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];

        $token = JWT::encode($payload, $key, 'HS256');    

        return $token;
    }

}