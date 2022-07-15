<?php

namespace App\Controllers\User\Http;

use Src\help\Json;
use Src\help\Request;
use App\Models\Repository\UsersRepository;

class Get
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     * @param Request $request
     * @param UsersRepository $usersRepository
     */
    public function __construct(Json $json, Request $request, UsersRepository $usersRepository)
    {
        $this->json = $json;
        $this->usersRepository = $usersRepository;
        $this->request = $request;
    }

    /**
     * create method HTTP
     *
     * @return json
     */
    public function create()
    {
        $email = $this->request->getParam('email');
        $user =  $this->usersRepository->get(['id', 'name', 'email'], ['email' => $email]);

        if (!empty($user->name)) {
            return $this->json->response($user, 200);
        }

        return $this->json->response(['message' => "bad request"], 400);
    }
}
