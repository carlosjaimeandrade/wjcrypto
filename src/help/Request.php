<?php

namespace Src\help;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Users;

class Request
{

    /**
     * @param Users $users
     */
    public function __construct(Users $users){
        $this->users = $users;
    }

    /**
     * request param value
     *
     * @param integer $param
     * @return string
     */
    public function getId(int $param): ?string 
    {
        $urlId = $_GET['url'];
        $urlId = explode("/", $urlId);
        $num = 1 + $param;
        if (isset($urlId[$num])) {
            $urlId = $urlId[$num];
            return $urlId;
        }else{
            return null;
        }
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
                
                $user = $this->users->findOne(['*'], ["email" => $decoded->email]);
               
                if(empty($user)){
                    return false;
                }

                if($data == false){
                    return true;
                }
                
                return $decoded;
            } catch (\Exception $e) { // Also tried JwtException
                return false;
            }
        }
    }

}
