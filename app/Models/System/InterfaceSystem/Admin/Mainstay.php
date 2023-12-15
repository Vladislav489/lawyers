<?php


namespace App\Models\System\InterfaceSystem\Admin;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

interface Mainstay {
    public function actionList(array $param = []) :JsonResponse;
    public function actionSave(array $param = []):JsonResponse;
    public function actionDelete(array $param = []):JsonResponse;
}
