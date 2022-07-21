<?php

namespace App\Controllers\History;

use App\Controllers\History\Http\Get;
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
     * @param Get $get
     * @param Json $json
     */
    public function __construct(Get $get, Json $json)
    {
        $this->get = $get;
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

        if ($httpMethod == "get") {
            $this->get->create();
            exit();
        }

        $this->json->response(['error' => "Method Not Allowed"], 405);
    }
}
