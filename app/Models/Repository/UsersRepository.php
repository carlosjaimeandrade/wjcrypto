<?php

namespace App\Models\Repository;

use App\Models\Users;

class UsersRepository{

    /**
     * @var Users
     */
    private $users;

    /**
     * @var int
     */
    public $lastInsertId = null;

    /**
     * @param Users $users
     */
    public function __construct(Users $users){
        $this->users = $users;
    }

    /**
     * get one item from database
     *
     * @param array $attributes
     * @param array $conditions
     * @return Users
     */
    public function get(array $attributes = ['*'], array $conditions = []){
        return $this->users->findOne($attributes, $conditions);
    }
    
    /**
     * get all item from database
     *
     * @param array $attributes
     * @param array $conditions
     * @return Users
     */
    public function all(array $attributes = ['*'], array $conditions = []){
        return $this->users->findAll($attributes, $conditions);
    }

    /**
     * create new register database
     *
     * @param $array $datas
     * @return void
     */
    public function create(array $datas){
        return $this->users->create((array) $datas);
    }
    
    /**
     * number de rows in database
     *
     * @param int $id
     * @return int
     */
    public function delete($id){
        return $this->users->delete($id);
    }
}