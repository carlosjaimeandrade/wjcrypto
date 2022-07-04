<?php

namespace App\Models;

use Src\help\Database;

abstract class QueryBuilder{

    public $table;

    /**
     * select all table
     *
     * @param array $attributes
     * @param array $conditions
     * @return array
     */
    public function findAll(array $attributes = ['*'], array $conditions = []){
        $col = "";
        $obj = [];
        foreach($attributes as $attribute){
            $col .= $attribute . " ,";
        }
        $col = rtrim($col,',');
        $pdo = Database::getConnection();
        $table = $this->table;

        if(count($conditions)>0){
            $params = "";
            foreach($conditions as $param => $value){
                $params .= "{$param}=:{$param} AND";
            }
            $params = rtrim($params,"AND");
            $stmt = $pdo->prepare("SELECT $col FROM $table WHERE $params");
            $stmt->execute($conditions); 

            while ($row = $stmt->fetchObject()) {
                $obj[] = $row;
            }
            return $obj;
        }

        $stmt = $pdo->query("SELECT $col FROM $table");
        while ($row = $stmt->fetchObject()) {
            $obj[] = $row;
        }
        return $obj;
    }

    /**
     * select one value table
     *
     * @param array $attributes
     * @param array $conditions
     * @return array 
     */
    public function findOne(array $attributes = ['*'], array $conditions = []){
        $col = "";
        foreach($attributes as $attribute){
            $col .= $attribute . " ,";
        }
        $col = rtrim($col,',');
        $pdo = Database::getConnection();
        $table = $this->table;

        if(count($conditions)>0){
            $params = "";
            foreach($conditions as $param => $value){
                $params .= "{$param}=:{$param} AND";
            }
            $params = rtrim($params,"AND");
            $stmt = $pdo->prepare("SELECT $col FROM $table WHERE $params");
            $stmt->execute($conditions); 

            return $stmt->fetchObject();
        }


        $stmt = $pdo->query("SELECT $col FROM $table");
  
        return $stmt->fetchObject();
    }


}