<?php


namespace App\Http\Mainstay\Client;


use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\Response;
// тут можено применять
class ClientController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'client') {
            return Response::json(["message" => "нет прав"]);
        }
        return parent::callAction($method, $parameters);
    }

    public function actionDeleteVacancy(Request $request)
    {
        if ($request->isMethod('delete')) {
            return response()->json((new VacancyLogic())->delete($request->input('id')));
        }
    }

    public function actionStoreVacancy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'payment' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->isMethod('post')) {
            $vacancy = (new VacancyLogic())->store($this->params);
        } elseif ($request->isMethod('patch')) {
            $vacancy = (new VacancyLogic())->update2($request->input('id'), $this->params);
        }

        return response()->json($vacancy);
    }
}
