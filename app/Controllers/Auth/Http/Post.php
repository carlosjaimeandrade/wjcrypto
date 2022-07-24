<?php

namespace App\Controllers\Auth\Http;

use Firebase\JWT\JWT;
use Src\help\Json;
use Src\help\Monolog;
use App\Models\Repository\UsersRepository;
use App\Models\Repository\HistoryRepository;

class Post
{

    /**
     * @var string
     */
    private $token;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * @var Monolog
     */
    private $monolog;

    /**
     * @param Json $json
     * @param UsersRepository $users
     * @param HistoryRepository $historyRepository
     * @param Monolog $monolog
     */
    public function __construct(Json $json, UsersRepository $users, HistoryRepository $historyRepository, Monolog $monolog)
    {
        $this->json = $json;
        $this->users = $users;
        $this->historyRepository = $historyRepository;
        $this->monolog = $monolog;
    }

    /**
     *  create hash for user
     *
     * @return json
     */
    public function create()
    {

        if ($this->userPermission()) {
            $this->json->response(['token' => $this->token], 200);
            exit();
        }

        $this->json->response(['error' => "Access denied."], 401);
    }

    /**
     * return status user permission
     *
     * @return bolean
     */
    private function userPermission()
    {
        $body = $this->json->request();

        if (empty($body['email']) or empty($body['password'])) {
            return false;
        }

        $password = md5($body['password']);
        $user = $this->users->get(['*'], ["email" => $body['email'],  "password" => $password]);

        if (empty($user->email)) {
            return false;
        }

        $this->token($user);
        return true;
    }

    /**
     * define token
     *
     * @return json
     */
    private function token($user): void
    {
        $key = "Aswd212$$@#as@ad2f58456s485a4as984d872";
        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'exp' => time() + 7200
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        $this->token = $token;

        $description = base64_encode('Login realizado');
        $category = base64_encode('login');
        $this->historyRepository->create(["description" => $description, "category" => $category, 'users_id' => $user->id]);
        $this->monolog->logger("New login user $user->name");
    }
}
