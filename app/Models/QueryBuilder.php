<?php

namespace App\Models;

use Src\help\Database;

abstract class QueryBuilder
{
    /**
     * @var string
     */
    public $table;

    public function __construct($atributes = [], $query = [], $define = false)
    {

        if($define){
            if (!is_array($atributes)) {
                foreach ($atributes as $key => $value) {
                    $this->$key = $value;
                }
                $this->find = "one";
            } else {
                $this->datas = $atributes;
                $this->find = "all";
            }
        }

        $this->query = $query;
    }

    /**
     * select all table
     *
     * @param array $attributes
     * @param array $conditions
     * @return array
     */
    public function findAll(array $attributes = ['*'], array $conditions = [])
    {
        $col = "";
        $obj = [];
        $query = [];
        foreach ($attributes as $attribute) {
            $col .= $attribute . " ,";
        }
        $col = rtrim($col, ',');
        $pdo = Database::getConnection();
        $table = $this->table;

        if (count($conditions) > 0) {
            $params = "";
            foreach ($conditions as $param => $value) {
                $params .= "{$param}=:{$param} AND ";
            }
            $params = rtrim($params, " AND ");
            $stmt = $pdo->prepare("SELECT $col FROM $table WHERE $params");
            $stmt->execute($conditions);

            while ($row = $stmt->fetchObject()) {
                $obj[] = $row;
            }

            $query['query'] = "SELECT $col FROM $table WHERE $params";
            $query['conditions'] = $conditions;

            return $this->newObj($obj,$query);
        }

        $stmt = $pdo->query("SELECT $col FROM $table");

        $query['query'] = "SELECT $col FROM $table";
        $query['conditions'] = "";

        while ($row = $stmt->fetchObject()) {
            $obj[] = $row;
        }


        return $this->newObj($obj,$query);
    }

    /**
     * select one value table
     *
     * @param array $attributes
     * @param array $conditions
     * @return array 
     */
    public function findOne(array $attributes = ['*'], array $conditions = [])
    {
        $obj = [];
        $query= [];
        $pdo = Database::getConnection();
        $col = "";
        foreach ($attributes as $attribute) {
            $col .= $attribute . " ,";
        }
        $col = rtrim($col, ',');
        $table = $this->table;

        if (count($conditions) > 0) {
            $params = "";
            foreach ($conditions as $param => $value) {
                $params .= "{$param}=:{$param} AND ";
            }
            $params = rtrim($params, " AND ");
            $stmt = $pdo->prepare("SELECT $col FROM $table WHERE $params");

            $query['query'] = "SELECT $col FROM $table WHERE $params";
            $query['conditions'] = $conditions;

            $stmt->execute($conditions);
            
            return $this->newObj($stmt->fetchObject(),$query);
        }

        $stmt = $pdo->query("SELECT $col FROM $table");
        
        $query['query'] = "SELECT $col FROM $table";
        $query['conditions'] = "";


        return $this->newObj($stmt->fetchObject(), $query);
    }

    public function create($datas)
    {
        $pdo = Database::getConnection();
        $columns = "";
        $values = "";
        foreach ($datas as $key => $value) {
            $columns .= "$key, ";
            $values .= ":$key, ";
        }
        $columns = "(" . rtrim($columns, ", ") . ")";
        $values = "(" . rtrim($values, ", ") . ")";

        $sql = "INSERT INTO users $columns VALUES $values";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute($datas)) {
            $this->lastInsertId = $pdo->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function order($order) 
    {
        $obj = [];
        $query = $this->query['query'] . " ORDER BY id $order";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($query);
        if ($this->query['conditions'] == "") {
            $stmt->execute();
            while ($row = $stmt->fetchObject()) {
                $obj[] = $row;
            }
            $queryParams = [];
            $queryParams['query'] = $query;
            $queryParams['conditions'] = $this->query['conditions'];
    
            return $this->newObj($obj, $queryParams);
            exit();
        }
        $stmt->execute($this->query['conditions']);
        while ($row = $stmt->fetchObject()) {
            $obj[] = $row;
        }

        $queryParams = [];
        $queryParams['query'] = $query;
        $queryParams['conditions'] = $this->query['conditions'];

        return $this->newObj($obj, $queryParams);
    }

    private function newObj($array,$query)
    {
        $class = get_called_class();
        return new $class($array,$query,true);
    }
}
