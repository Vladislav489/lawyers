<?php

namespace App\Http\Controllers;

use App\Http\Mainstay\File\FileMainstayController;
use App\Models\System\ControllersModel\CentralController;
use App\Models\System\ControllersModel\FrontController;
use Illuminate\Routing\Route;

class Controller extends CentralController {
    public function getPageParams(): array {
        return [
            'actionIndex' => ['template' => 'lawyers.site.index'],
            'actionFindSpecialist' => ['template' => 'lawyers.employee.find-specialist'],
            'actionSpecialistCard' => ['name'=>"Профиль юриста",'type_page'=>'home','chpu'=>['employee_id'],'template' => 'lawyers.employee.profile'],
        ];
    }

    public function actionIndex() {
        return view('lawyers.site.index');
    }

    public function actionFindSpecialist() {
        return view('lawyers.employee.find-specialist');
    }

    public function actionSpecialistCard() {
        return view('lawyers.employee.profile');
    }

    public function viewFile() {
        $filePath = request()->query('path');
//        $fileName = request()->query('name');
        return (new FileMainstayController())->actionDownloadFile($filePath);
    }

}
