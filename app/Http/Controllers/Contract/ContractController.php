<?php

namespace App\Http\Controllers\Contract;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class ContractController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionContractList' => [
                'name' => 'Contract list',
                'template' => 'lawyers.contract.contract-list'
            ],
            'actionContractCreate' => [
                'name' => 'Contract create',
                'template' => 'lawyers.contract.contract-create'
            ],
            'actionContractEdit' => [
                'name' => 'Contract edit',
                'template' => 'lawyers.contract.contract-edit'
            ],
        ];
    }

    // TODO:
    public function callAction($method, $parameters)
    {
        if (true) {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionContractList()
    {
        return view('lawyers.contract.contract-list');
    }

    public function actionContractCreate()
    {
        return view('lawyers.contract.contract-create');
    }

    public function actionContractEdit()
    {
        return view('lawyers.contract.contract-edit');
    }
}
