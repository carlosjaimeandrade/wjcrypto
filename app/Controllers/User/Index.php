<?php

namespace App\Controllers\User;

use App\Controllers\User\Http\Post;
use Src\help\Json;

class Index
{

    public function __construct(Post $post, Json $json)
    {
        $this->post = $post;
        $this->json = $json; 
    }

    public function index()
    {
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

        if ($httpMethod == "post") {
            $this->post->create();
            exit();
        }

        $this->json->response(['error' => "Access denied."], 401); 
    }
}
