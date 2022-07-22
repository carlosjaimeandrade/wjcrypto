<?php

namespace App\Controllers\History\Http;

use Src\help\Json;
use Src\help\Request;
use App\Models\Repository\HistoryRepository;
use Data;

class Get
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     * @param Users $users
     */
    public function __construct(Json $json, Request $request, HistoryRepository $historyRepository)
    {
        $this->json = $json;
        $this->request = $request;
        $this->historyRepository = $historyRepository;
    }

    /**
     * get history
     *
     * @return json
     */
    public function create()
    {
        $category = $this->request->getParam('category');

        if(empty($category)){
            $this->json->response(['message' => "bad request"], 400);
            exit();
        }

        $user = $this->request->authorization(true);
        $userId = $user['id'];
        $category = base64_encode($category);
        $historys = $this->historyRepository->all(['*'],['category' => $category, 'users_id' => $userId]);
        if(!$historys){
            $this->json->response(['message' => "bad request"], 400);
        }

        $log = [];
        foreach($historys->datas as $key => $history){
            $date = date_create($history->createdAt);
            $log[] = ['description' => base64_decode($history->description), 'createdAt' => date_format($date, 'd-m-Y H:i:s')];
        }
       
        $this->json->response($log, 200);
    }
}
