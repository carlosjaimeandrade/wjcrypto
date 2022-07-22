<?php

namespace App\Controllers\Removal\Http;

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

        if (!$this->validateInput($data)) {
            $this->json->response(['error' => "Bad request."], 400);
            exit();
        }

        $user = $this->request->authorization(true);

        if (!$user) {
            $this->json->response(['error' => "Access denied."], 401);
            exit();
        }

        $id = $user['id'];

        $account = $this->accountsRepository->get(['*'], ['users_id' => $id]);
        $valueAccount = base64_decode($account->value);

        if ($valueAccount <= 0) {
            $this->json->response(['error' => "you don't have balance"], 400);
            exit();
        }

        if ($valueAccount - $data['value'] < 0) {
            $this->json->response(['error' => "you don't have balance"], 400);
            exit();
        }

        $newValue = base64_encode($valueAccount - $data['value']);

        if (!$this->accountsRepository->update(['value' => $newValue], $account->id)) {
            $this->json->response(['error' => "Access denied."], 400);
            exit();
        }

        $removal = number_format($data['value'], 2, ",", ".");
        $description = base64_encode("Retira de $removal");
        $category = base64_encode('removal');
        $this->historyRepository->create(["description" => $description, "category" => $category , 'users_id' => $id]);

        $this->json->response(['message' => "success"], 200);
    }


    /**
     * validate value request
     *
     * @param array $data
     * @return bolean
     */
    private function validateInput($data)
    {
        if (empty($data['value'])) {
            return false;
        }

        if (!is_numeric($data['value'])) {
            return false;
        }

        return true;
    }
}
