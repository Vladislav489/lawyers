<?php

namespace App\Http\Controllers;

use App\Models\System\ControllersModel\FrontController;

class Controller extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionMain' => ['template' => 'lawyers.site.index'],
        ];
    }

    public function actionMain()
    {
        //Лучше или home или main
        return view('lawyers.site.index');
    }
}
