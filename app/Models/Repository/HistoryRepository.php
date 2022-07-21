<?php

namespace App\Models\Repository;

use App\Models\History;

class HistoryRepository{

    /**
     * @var History
     */
    private $history;

    /**
     * @param history $history
     */
    public function __construct(History $history){
        $this->history = $history;
    }

    /**
     * get one item from database
     *
     * @param array $attributes
     * @param array $conditions
     * @return History
     */
    public function get(array $attributes = ['*'], array $conditions = []){
        return $this->history->findOne($attributes, $conditions);
    }
    
    /**
     * get all item from database
     *
     * @param array $attributes
     * @param array $conditions
     * @return History
     */
    public function all(array $attributes = ['*'], array $conditions = []){
        return $this->history->findAll($attributes, $conditions);
    }

    /**
     * create new register database
     *
     * @param $array $datas
     * @return void
     */
    public function create(array $datas){
        return $this->history->create((array) $datas);
    }

    /**
     * number de rows in database
     *
     * @param int $id
     * @return int
     */
    public function delete($id){
        return $this->history->delete($id);
    }

    /**
     * update value on database
     *
     * @param array $datas
     * @param int $id
     * @return bolean
     */
    public function update($datas, $id){
        return $this->history->update($datas,$id);
    }
}