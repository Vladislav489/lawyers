<?php

namespace App\Http\Mainstay\Contract;

use App\Models\CoreEngine\LogicModels\Contract\ContractLogic;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContractMainstayController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionStoreContract(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $request->merge([
            'user_id' => Auth::id(),
        ]);

        return response()->json(
            (new ContractLogic())->store($request->all())
        );
    }

    public function actionGetContractList()
    {
        return response()->json((new ContractLogic())->getList());
    }
}
