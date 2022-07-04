<?php

namespace Src\help;

use PDO;
use PDOException;

class Database
{
    /**
     * connection PDO database
     *
     * @return PDO
     */
    public static function getConnection()
    {
        try {
            echo realpath(dirname(__FILE__));
            $envPath = realpath(dirname(__FILE__) . '../../../env.ini');
            $env = parse_ini_file($envPath);

            $host = $env['host'];
            $database = $env['database'];
            $username = $env['username'];
            $password =  $env['password'];
   
            $conn = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            echo 'Erro de conexão' ;
        }
    }
}
