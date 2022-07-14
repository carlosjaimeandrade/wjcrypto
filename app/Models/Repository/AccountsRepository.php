<?php

namespace App\Models\Repository;

use App\Models\Accounts;

class AccountsRepository{

    /**
     * @var Accounts
     */
    private $accounts;

    /**
     * @param Accounts $accounts
     */
    public function __construct(Accounts $accounts){
        $this->accounts = $accounts;
    }

    /**
     * get one item from database
     *
     * @param array $attributes
     * @param array $conditions
     * @return void
     */
    public function get(array $attributes = ['*'], array $conditions = []){
        return $this->accounts->findOne($attributes, $conditions);
    }
    
    /**
     * get all item from database
     *
     * @param array $attributes
     * @param array $conditions
     * @return void
     */
    public function all(array $attributes = ['*'], array $conditions = []){
        return $this->accounts->findAll($attributes, $conditions);
    }

    /**
     * create new register database
     *
     * @param $array $datas
     * @return void
     */
    public function create(array $datas){
        return $this->accounts->create((array) $datas);
    }

    /**
     * number de rows in database
     *
     * @param int $id
     * @return int
     */
    public function delete($id){
        return $this->accounts->delete($id);
    }
}