<?php

namespace App\Http\Mainstay\HelpData;

use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;

class HelpDataMainstayController extends MainstayController
{
    public function actionGetCities()
    {
        return response()->json(DB::table('city')->limit(100)->get());
    }

    public function actionGetDistricts()
    {
        return response()->json(DB::table('district')->limit(100)->get());
    }

    public function actionGetCountries()
    {
        return response()->json(DB::table('country')->limit(100)->get());
    }

    public function actionGetStates2()
    {
        return response()->json(DB::table('state')->limit(100)->get());
    }
}
