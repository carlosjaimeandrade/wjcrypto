<?php

namespace App\Controllers\Auth\Http;

use Src\help\Json;
use Src\help\Request;

class Get
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     * @param Users $users
     */
    public function __construct(Json $json, Request $request)
    {
        $this->json = $json;
        $this->request = $request;
    }

    public function create()
    {
        return $this->json->response($this->request->authorization(true),200);
    }
}