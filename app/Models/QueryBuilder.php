<?php

namespace App\Models;

use Src\help\Database;
use PDOException;
use PDO;

abstract class QueryBuilder
{
    const OBJECT_ALL = "objectAll";
    const OBJECT_ONE = "objectOne";

    /**
     * @var string
     */
    protected $table;

    public function __construct($atributes = [], $define = false)
    {

        if ($define) {
            if (!is_array($atributes)) {
                if (!empty($atributes)) {
                    foreach ($atributes as $key => $value) {
                        $this->$key = $value;
                    }
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
        $table = $this->table;
        
        foreach ($attributes as $attribute) {
            $col .= $attribute . " ,";
        }
        $col = rtrim($col, ',');
       
        if (count($conditions) == 0) {
            $sql = "SELECT $col FROM $table";
            return $this->execute($sql, $conditions,'objectAll', 'all');
        }

        $params = "";
        foreach ($conditions as $param => $value) {
            $params .= "{$param}=:{$param} AND ";
        }
        $params = rtrim($params, " AND ");

        $sql = "SELECT $col FROM $table WHERE $params";
        return $this->execute($sql, $conditions,'objectAll', 'all');
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
        $col = "";
        foreach ($attributes as $attribute) {
            $col .= $attribute . ", ";
        }
        $col = rtrim($col, ', ');
        $table = $this->table;
        
        if (count($conditions) == 0) {
            $sql = "SELECT $col FROM $table";
            return $this->execute($sql, $conditions,'objectOne', 'one');
        }

        $params = "";
        foreach ($conditions as $param => $value) {
            $params .= "{$param}=:{$param} AND ";
        }
        $params = rtrim($params, " AND ");

        $sql = "SELECT $col FROM $table WHERE $params";
        return $this->execute($sql, $conditions,'objectOne', 'one');
    }

    /**
     * create data in database
     *
     * @param array $datas
     * @return bolean
     */
    public function create(array $datas)
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

        $table = $this->table;

        try {
            $sql = "INSERT INTO $table $columns VALUES $values";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($datas)) {
                return $pdo->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

        /**
     * delete one data in database
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        $pdo = Database::getConnection();
        $table = $this->table;
        $query = "DELETE FROM $table WHERE id = :id";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute(['id' => $id])) {
            return true;
        }

        return false;
    }

    /**
     * update value on database
     *
     * @param array $datas
     * @param int $id
     * @return bolean
     */
    public function update(array $datas,int $id){
        $pdo = Database::getConnection();
        $set = "";
        foreach($datas as $key => $value){
            $set .= "$key=:$key, ";
        }

        $set = rtrim($set, ', ');
        $set = "$set WHERE id=:id";

        $table = $this->table;
        $datas['id'] = $id;
        try {
            $sql = "UPDATE $table SET $set";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($datas)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }

    }

    /**
     * create obj
     *
     * @param array $array
     * @param array $query
     * @return object
     */
    private function newObj($attributes, $query)
    {
        $class = get_called_class();
        return new $class($attributes, $query, true);
    }

    /**
     * execute pdo query
     *
     * @param string $sql
     * @param array $conditions
     * @param string $response
     * @param null|string $find
     * @return bolean|object
     */
    private function execute($sql, $conditions, $response, $find = null)
    {
        try {
            $query = [
                'query' => $sql,
                'conditions' => $conditions,
                'find' => $find
            ];
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($conditions);
           
            if ($response == self::OBJECT_ALL) {
                $obj = [];
                while ($row = $stmt->fetchObject()) {
                    $obj[] = $row;
                }
                return $this->newObj($obj, $query);
            }
            if ($response == self::OBJECT_ONE) {
                return $this->newObj($stmt->fetchObject(), $query);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
