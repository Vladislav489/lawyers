<?php

namespace App\Http\Controllers;

use App\Models\System\ControllersModel\FrontController;

class Controller extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionMain' => ['template' => '1'],
        ];
    }

    public function actionMain()
    {
        return view('lawyers.site.index');
    }
}
