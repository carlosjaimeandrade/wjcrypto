<?php

namespace App\Controllers\User;

use App\Controllers\User\Http\Post;
use Src\help\Json;
use App\Models\Users;

class Index
{

    /**
     * @param Post $post
     * @param Json $json
     */
    public function __construct(Post $post, Json $json)
    {
        $this->post = $post;
        $this->json = $json; 
    }

    /**
     * get http request
     *
     * @return void
     */
    public function index()
    {
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

        if ($httpMethod == "post") {
            $this->post->create();
            exit();
        }

        if ($httpMethod == "get") {
       
        }

        $this->json->response(['error' => "Access denied."], 401); 
    }
}
