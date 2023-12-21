<?php

namespace App\Http\Controllers;

use App\Models\System\ControllersModel\FrontController;

class Controller extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionIndex' => ['template' => 'lawyers.site.index'],
        ];
    }

    public function actionIndex()
    {
        return view('lawyers.site.index');
    }
}
