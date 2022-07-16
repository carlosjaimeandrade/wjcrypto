<?php

namespace App\Controllers\Removal;

use App\Controllers\Removal\Http\Post;
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

        $this->json->response(['error' => "Method Not Allowed"], 405);
    }
}
