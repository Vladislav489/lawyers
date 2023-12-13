<?php

namespace App\Http\Mainstay\Company;

use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;

class CompanyMainstayController extends MainstayController
{
    public function actionGetCompanies()
    {
        return response()->json(DB::table('company')->limit(100)->get());
    }
}
