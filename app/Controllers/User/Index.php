<?php

namespace App\Controllers\User;

use App\Controllers\User\Http\Post;
use App\Controllers\User\Http\Get;
use Src\help\Json;


class Index
{

    /**
     * @var Post
     */
    private $post;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var Get
     */
    private $get;

    /**
     * @param Post $post
     * @param Json $json
     */
    public function __construct(Post $post, Json $json, Get $get)
    {
        $this->post = $post;
        $this->json = $json;
        $this->get = $get;
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
            $this->get->create();
            exit();
        }

        $this->json->response(['error' => "Access denied."], 401);
    }
}
