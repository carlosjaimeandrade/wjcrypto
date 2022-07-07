<?php

namespace App\Controllers\Auth;

use  App\Controllers\Auth\Http\Post;

class Index{

    public function __construct(Post $post){
        $this->post = $post; 
    }

    public function index(){
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

        if($httpMethod == "post"){
            $this->post->create();
        }
        
    }
}