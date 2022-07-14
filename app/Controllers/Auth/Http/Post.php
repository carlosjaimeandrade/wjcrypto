<?php

namespace App\Controllers\Auth\Http;

use Firebase\JWT\JWT;
use Src\help\Json;
use App\Models\Repository\UsersRepository;

class Post{

    /**
     * @var string
     */
    private $token;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     * @param Users $users
     */
    public function __construct(Json $json, UsersRepository $users){
        $this->json = $json;
        $this->users = $users;
    }

    /**
     * create method HTTP
     *
     * @return json
     */
    public function create(){

        if($this->userPermission()){
            $this->json->response(['token'=> $this->token], 200);
            exit();
        }

        $this->json->response(['error' => "Access denied."], 401); 
    }

    /**
     * return status user permission
     *
     * @return bolean
     */
    private function userPermission(){
        $body = $this->json->request();

        if(empty($body['email']) OR empty($body['password'])){
            return false;
        }

        $password = md5($body['password']);
        $user = $this->users->get(['*'], ["email" => $body['email'],  "password" => $password]);
        
        if(empty($user->email)){
            return false;
        }

        $this->token($user);
        return true;
    }

    /**
     * define token
     *
     * @return void
     */
    private function token($user):void{
        $key = "Aswd212$$@#as@ad2f58456s485a4as984d872";
        $payload = [
            'id' => $user->id ,
            'name' => $user->name,
            'email' => $user->email,
            'exp' => time() + 3600
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        $this->token = $token;
    }

}