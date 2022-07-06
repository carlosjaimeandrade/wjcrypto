<?php

namespace App\Controllers\Home;

use App\Models\Produto;
use Psr\Http\Server\RequestHandlerInterface;

class Index{

    public function __construct(Produto $produto){
        $this->produto = $produto;
    }
    
    public function index(){    
        echo "pagina inicial";
    }

}