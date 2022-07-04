<?php

namespace App\Controllers\Home;

use App\Models\Produto;


class Index{

    public function __construct(Produto $produto){
        $this->produto = $produto;
    }
    
    public function index(){    
        
        $dados = $this->produto->findOne(['*'], ['email' => 'joao1@hotmail.com']);
        var_dump($dados);
        echo "pagina inicial";
    }

}