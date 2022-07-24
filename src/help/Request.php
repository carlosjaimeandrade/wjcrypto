<?php

namespace Src\help;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Repository\UsersRepository;
use App\Models\Repository\AccountsRepository;
use Src\help\Json;
use Src\help\Monolog;

class Request
{
    /**
     * @var UsersRepository
     */
    private $users;

    /**
     * @var AccountsRepository
     */
    private $accounts;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param UsersRepository $users
     * @param AccountsRepository $accounts
     * @param Json $json
     * @param Monolog $monolog
     */
    public function __construct(UsersRepository $users, AccountsRepository $accounts, Json $json, Monolog $monolog){
        $this->users = $users;
        $this->accounts = $accounts;
        $this->json = $json;
        $this->monolog = $monolog;
    }

    /**
     * request param value
     *
     * @param integer $param
     * @return string
     */
    public function getParam($param): ?string 
    {
        if(isset($_GET["$param"])){
            return $_GET["$param"];
        }

        return null;
    }

    /**
     * return status information in token
     *
     * @param boolean $data
     * @param string $code
     * @return array|bolean
     */
    public function authorization($data = false, $code = null){
        $key = "Aswd212$$@#as@ad2f58456s485a4as984d872";
        $headers = getallheaders();
        if(!empty($headers['Authorization'])){
            if($code == null){
                $token = explode("Bearer ", $headers['Authorization'])[1];
            }else{
                $token = $code;
            }
            try {
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                $user = $this->users->get(['*'], ["email" => $decoded->email]);
                $account = $this->accounts->get(['*'], ['users_id' => $user->id]);
          
              
                $dataUser = [
                    'id' => $decoded->id,
                    'name' => $decoded->name,
                    'email' => $decoded->email,
                    'account' => base64_decode($account->account),
                    'value' => base64_decode($account->value),
                ];
          

                if(empty($user)){
                    return false;
                }

                if($data == false){
                    return true;
                }
            
                return $dataUser;
            } catch (\Exception $e) { // Also tried JwtException
                return false;
            }
        }
    }

}
