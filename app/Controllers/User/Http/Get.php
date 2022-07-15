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

    public function create()
    {
        $id = $this->request->getParam('id');
        $user =  $this->usersRepository->get(['id', 'name', 'email'], ['id' => $id]);

        if(!empty($user->name)){
            return $this->json->response($user, 200);
        }
        
        return $this->json->response(['message'=> "bad request"], 400);  
    }
}
