<?php

namespace App\Controllers\Transfer\Http;

use App\Models\Repository\AccountsRepository;
use App\Models\Repository\UsersRepository;
use Src\help\Request;
use Src\help\Json;

class Post
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var AccountsRepository
     */
    private $accountsRepository;

    /**
     * @var UsersRepository
     */
    private  $users;

    /**
     * @param AccountsRepository $accountsRepository
     */
    public function __construct(AccountsRepository $accountsRepository, UsersRepository $users, Request $request, Json $json){
        $this->accountsRepository = $accountsRepository;
        $this->request = $request;
        $this->json = $json;
        $this->users =  $users;
    }
    /**
     * deposit new value in account
     *
     * @return json
     */
    public function create()
    {
        $data = $this->json->request();

        if(!$this->removalValueAccout($data)){
            $this->json->response(['error' => "Bad request"], 400);
        }

        if(!$this->transfer($data)){
            $this->json->response(['error' => "Bad request"], 400);
        }

        $this->json->response(['message' => "success"], 400);
    }


    /**
     * validate value request
     *
     * @param array $data
     * @return bolean
     */
    private function validateInput($data){
        if(empty($data['value'])){
           return false;
        }

        if(!is_numeric($data['value'])){
            return false;
        }

        if(empty($data['email'])){
            return false;
         }

        if(!is_string($data['email'])){
            return false;
        }

        if(count($data) != 2){
            return false;
        }

        return true;
    }

    /**
     * update value in database for transfer
     *
     * @param array $data
     * @return bolean
     */
    private function removalValueAccout($data){
        
        if(!$this->validateInput($data)){
            return false;
        }
    
        $user = $this->request->authorization(true);
        
        if(!$user){
            return false;
        }

        $id = $user['id'];
        $account = $this->accountsRepository->get(['*'], ['users_id' => $id]);
        $valueAccount = base64_decode($account->value);

        if($valueAccount <= 0){
            return false;
        }

        if($valueAccount - $data['value'] < 0){
            return false;
        }

        $newValue = base64_encode($valueAccount - $data['value']);
       
        if(!$this->accountsRepository->update(['value'=> $newValue], $account->id)){
            return false;
        }
        
        return true;
    }

    private function transfer($data){

        $user = $this->users->get(['*'], ['email' => $data['email']]);
       
        $account = $this->accountsRepository->get(['*'], ['users_id' => $user->id]);
        $valueAccount = base64_decode($account->value);

        $newValue = base64_encode($valueAccount + $data['value']);
       
        if(!$this->accountsRepository->update(['value'=> $newValue], $account->id)){
            return false;
        }

        return true;
    }
}
