<?php

namespace App\Controllers\Deposit\Http;

use App\Models\Repository\AccountsRepository;
use App\Models\Repository\HistoryRepository;
use Src\help\Request;
use Src\help\Json;

class Post
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var AccountsRepository
     */
    private $accountsRepository;

    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * @param AccountsRepository $accountsRepository
     */
    public function __construct(
        AccountsRepository $accountsRepository,
        Request $request,
        Json $json,
        HistoryRepository $historyRepository
    ) {
        $this->accountsRepository = $accountsRepository;
        $this->request = $request;
        $this->json = $json;
        $this->historyRepository = $historyRepository;
    }
    /**
     * deposit new value in account
     *
     * @return json
     */
    public function create()
    {
        $data = $this->json->request();

        if (empty($data['value'])) {
            $this->json->response(['error' => "Bad request."], 400);
            exit();
        }

        if (!is_numeric($data['value'])) {
            $this->json->response(['error' => "Bad request."], 400);
            exit();
        }

        $user = $this->request->authorization(true);


        if (!$user) {
            $this->json->response(['error' => "Access denied."], 401);
        }

        $id = $user['id'];
        $account = $this->accountsRepository->get(['*'], ['users_id' => $id]);
        $valueAccount = base64_decode($account->value);
        $newValue = base64_encode($data['value'] + $valueAccount);
        if (!$this->accountsRepository->update(['value' => $newValue], $account->id)) {
            $this->json->response(['error' => "Access denied."], 400);
        }

        $deposit = number_format($data['value'],2,",",".");
        $description = base64_encode("Depósito de $deposit");
        $category = base64_encode('deposit');
        $this->historyRepository->create(["description" => $description, "category" =>  $category, 'users_id' => $id]);
        
        $this->json->response(['message' => "success"], 200);
    }
}
