<?php

namespace App\Models;

use Src\help\Database;

abstract class QueryBuilder
{

    private $query;

    private $conditions;

    /**
     * @var int
     */
    public $lastInsertId;

    /**
     * @var string
     */
    public $table;

    public function __construct($atributes = [], $define = false)
    {

        if($define){
            if (!is_array($atributes)) {
                foreach ($atributes as $key => $value) {
                    $this->$key = $value;
                }
            } else {
                $this->datas = $atributes;
            }
        }

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

            $this->query = "SELECT $col FROM $table WHERE $params";
            $this->conditions = $conditions;

            return $this->newObj($obj);
        }

        $stmt = $pdo->query("SELECT $col FROM $table");
        $this->query = "SELECT $col FROM $table";
        $this->conditions = "";

        while ($row = $stmt->fetchObject()) {
            $obj[] = $row;
        }


        return $this->newObj($obj);
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
            $stmt->execute($conditions);

            return $this->newObj($stmt->fetchObject());
        }


        $stmt = $pdo->query("SELECT $col FROM $table");

        return $this->newObj($stmt->fetchObject());
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
        $query = $this->query . " ORDER BY id $order";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($query);
        if ($this->conditions == "") {
            $stmt->execute();
            while ($row = $stmt->fetchObject()) {
                $obj[] = $row;
            }
            return $obj;
            exit();
        }
        $stmt->execute($this->conditions);
        while ($row = $stmt->fetchObject()) {
            $obj[] = $row;
        }

        return $stmt->fetchObject();
    }

    private function newObj($array)
    {
        $class = get_called_class();
        return new $class($array,true);
    }
}
