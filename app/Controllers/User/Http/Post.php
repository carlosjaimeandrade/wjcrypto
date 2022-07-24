<?php

namespace App\Controllers\User\Http;

use Src\help\Json;
use Src\help\Monolog;
use App\Models\Repository\UsersRepository;
use App\Models\Repository\AccountsRepository;

class Post
{

    /**
     * @var ?int
     */
    private $userId = null;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var UsersRepository
     */
    private $users;

    /**
     * @var AccountsRepository
     */
    private $accounts;

    /**
     * @param Json $json
     * @param UsersRepository $users
     * @param AccountsRepository $accounts
     * @param Monolog $monolog
     */
    public function __construct(Json $json, UsersRepository $users, AccountsRepository $accounts, Monolog $monolog)
    {
        $this->json = $json;
        $this->users = $users;
        $this->accounts = $accounts;
        $this->monolog = $monolog;
    }

    /**
     * create new user in database
     *
     * @return json
     */
    public function create()
    {
        if ($this->emailIsDuplicate()) {
            $this->json->response(['error' => "user already registered"], 400);
            exit();
        }

        if (!$this->newUser()) {
            $this->json->response(['message' => "bad request"], 400);
            exit();
        }

        if (!$this->newAccount()) {
            $this->rollbackUser();
            $this->json->response(['message' => "bad request"], 400);
            exit();
        }


        $this->json->response(['message' => "success"], 200);
    }

    /**
     * create new user
     *
     * @return bolean
     */
    private function newUser()
    {
        $body = $this->json->request();

        if (count($body) != 9) {
            return false;
        }

        $body['password'] = md5($body['password']);

        $this->userId = $this->users->create($body);
        if (!$this->userId) {
            return false;
            exit();
        }

        $this->monolog->logger("New user create {$body['name']}");
        return true;
    }

    /**
     * gerate new account
     *
     * @return bolean
     */
    private function newAccount()
    {
        if ($this->userId == null) {
            return false;
            exit();
        }

        $account = $this->userId . rand(000, 100);
        $account = base64_encode($account);
        $value = base64_encode(0);

        if ($this->accounts->create(['account' => $account, 'value' => $value, 'users_id' => $this->userId])) {
            $this->monolog->logger("New account create $account");
            return true;
            exit();
        }

        return false;
    }

    /**
     * check duplicate register
     *
     * @return bolean
     */
    private function emailIsDuplicate()
    {
        $body = $this->json->request();
        if (empty($body['email'])) {
            $this->json->response(['message' => "bad request"], 400);
            exit();
        }
        $user = $this->users->get(['*'], ['email' => $body['email']]);
        if (empty($user->name)) {
            return false;
        }

        return true;
    }

    /**
     * restory insert user
     *
     * @return bolean
     */
    private function rollbackUser()
    {
        if ($this->userId == null) {
            return false;
            exit();
        }

        if ($this->users->delete($this->userId)) {
            $id = $this->userId;
            $this->monolog->logger("Error create new account user $id",'error');
            return true;
        }
    }
}
