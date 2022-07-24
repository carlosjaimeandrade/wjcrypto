<?php

namespace App\Controllers\Transfer\Http;

use App\Models\Repository\AccountsRepository;
use App\Models\Repository\UsersRepository;
use App\Models\Repository\HistoryRepository;
use Src\help\Request;
use Src\help\Json;
use Src\help\Monolog;

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
     * @var UsersRepository
     */
    private  $users;

    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * @var Monolog
     */
    private $monolog;

    /**
     * @param AccountsRepository $accountsRepository
     * @param UsersRepository $users
     * @param Request $request
     * @param Json $json
     * @param HistoryRepository $historyRepository
     * @param Monolog $monolog
     */
    public function __construct(
        AccountsRepository $accountsRepository,
        UsersRepository $users,
        Request $request,
        Json $json,
        HistoryRepository $historyRepository,
        Monolog $monolog
    ) {
        $this->accountsRepository = $accountsRepository;
        $this->request = $request;
        $this->json = $json;
        $this->users =  $users;
        $this->historyRepository = $historyRepository;
        $this->monolog = $monolog;
    }
    /**
     * deposit new value in account
     *
     * @return json
     */
    public function create()
    {
        $data = $this->json->request();

        if (!$this->removalValueAccount($data)) {
            $this->json->response(['error' => "Bad request"], 400);
            exit();
        }

        if (!$this->transfer($data)) {
            $this->json->response(['error' => "Bad request"], 400);
            exit();
        }

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

        if (empty($data['email'])) {
            return false;
        }

        if (!is_string($data['email'])) {
            return false;
        }

        if (count($data) != 2) {
            return false;
        }

        return true;
    }

    /**
     * update value in database for transfer
     *
     * @param array $data
     * @return bolean
     */
    private function removalValueAccount($data)
    {

        if (!$this->validateInput($data)) {
            return false;
        }

        $user = $this->request->authorization(true);

        if (!$user) {
            return false;
        }

        $id = $user['id'];
        $account = $this->accountsRepository->get(['*'], ['users_id' => $id]);
        $valueAccount = base64_decode($account->value);

        if ($valueAccount <= 0) {
            return false;
        }

        if ($valueAccount - $data['value'] < 0) {
            return false;
        }

        $newValue = base64_encode($valueAccount - $data['value']);

        if (!$this->accountsRepository->update(['value' => $newValue], $account->id)) {
            return false;
        }

        return true;
    }

    /**
     * Transfer new value a account
     *
     * @param array $data
     * @return bolean
     */
    private function transfer($data)
    {
        $userEmiter = $this->request->authorization(true);
        $id = $userEmiter['id'];

        $userReceiver = $this->users->get(['*'], ['email' => $data['email']]);

        if(empty($userReceiver->id)){
            return false;
        }

        $account = $this->accountsRepository->get(['*'], ['users_id' => $userReceiver->id]);
        $valueAccount = base64_decode($account->value);

        $newValue = base64_encode($valueAccount + $data['value']);

        if (!$this->accountsRepository->update(['value' => $newValue], $account->id)) {
            return false;
        }

        $transferValue = number_format($data['value'], 2, ",", ".");
        $name = $userReceiver->name;
       
        $description = base64_encode("Transferência de $transferValue para $name");
        $category = base64_encode('transfer');
        $this->historyRepository->create(["description" => $description , "category" =>  $category, 'users_id' => $id]);
       
        $name = $userEmiter['name'];
        $category = base64_encode('deposit');
        $description = base64_encode("Recebido valor de $transferValue enviado por $name");
        $this->historyRepository->create(["description" => $description , "category" =>  $category, 'users_id' => $userReceiver->id]);
        $valueTransfer = base64_encode($data['value']);
        $this->monolog->logger("transfer from {$valueTransfer} to $userReceiver->name sent from {$userEmiter['name']}");
        return true;
    }
}
