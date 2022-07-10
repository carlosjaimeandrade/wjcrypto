<?php

namespace App\Controllers\User\Http;

use Src\help\Json;
use App\Models\Users;
use App\Models\Accounts;

class Post{

    public function __construct(Json $json, Users $users, Accounts $accounts){
        $this->json = $json;
        $this->users = $users;
        $this->accounts = $accounts;
    }

    public function create(){
        
       var_dump($this->users->findAll()->order('ASC'));
        
       //$this->newUser();
    }

    public function newUser(){
        $body = $this->json->request();
        $body['password'] = md5($body['password']);

        if(!$this->users->create($body)){
            $this->json->response(['error'=> "Bad Request"], 400);
            exit();
        }

        $this->json->response(['message'=> "success"], 200);
    }

    public function newAccount($idUser){
        
    }

}