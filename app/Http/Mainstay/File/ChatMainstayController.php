<?php

namespace App\Http\Mainstay\File;

use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileMainstayController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionStoreFile(Request $request)
    {

    }
}
