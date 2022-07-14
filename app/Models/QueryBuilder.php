<?php

namespace App\Models;

use Src\help\Database;
use PDOException;
use PDO;

abstract class QueryBuilder
{
    /**
     * @var string
     */
    public $table;

    public function __construct($atributes = [], $query = [], $define = false)
    {

        if ($define) {
            if (!is_array($atributes)) {
                if (!empty($atributes)) {
                    foreach ($atributes as $key => $value) {
                        $this->$key = $value;
                    }
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
    public function delete($id)
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
     * Order return mysql datas
     *
     * @param string
     * @return object
     */
    public function order(string $order)
    {
        $obj = [];
        $query = $this->query['query'] . " ORDER BY id $order";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($query);
        if ($this->query['conditions'] == "") {
            $stmt->execute();

            $queryParams = [
                'query' => $query,
                'conditions' => $this->query['conditions'],
            ];

            if ($this->query['find'] == "all") {
                while ($row = $stmt->fetchObject()) {
                    $obj[] = $row;
                }

                return $this->newObj($obj, $queryParams);
                exit();
            }

            return $this->newObj($stmt->fetchObject(), $queryParams);
        }

        $stmt->execute($this->query['conditions']);

        $queryParams = [
            'query' => $query,
            'conditions' => $this->query['conditions'],
        ];

        if ($this->query['find'] == "all") {
            while ($row = $stmt->fetchObject()) {
                $obj[] = $row;
            }

            return $this->newObj($obj, $queryParams);
            exit();
        }

        return $this->newObj($stmt->fetchObject(), $queryParams);
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
           
            if ($response == "objectAll") {
                $obj = [];
                while ($row = $stmt->fetchObject()) {
                    $obj[] = $row;
                }
                return $this->newObj($obj, $query);
            }
            if ($response == "objectOne") {
                return $this->newObj($stmt->fetchObject(), $query);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
