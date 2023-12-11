<?php

namespace App\Http\Controllers;

use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\System\ControllersModel\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionEmployeeCabinet' => ['template' => '1'],
            'actionEmployeeSettings' => ['template' => '1'],
            'actionEmployeeStore' => ['template' => '1'],
            'actionEmployeeServiceUpdate' => ['template' => '1'],
        ];
    }

    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'employee') {
            return redirect('/main');
        }

        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeCabinet()
    {
        return view('lawyers.employee.cabinet');
    }

    public function actionEmployeeSettings()
    {
        $services = Service::all();
        $user_service_ids = array_column(Auth::user()->services->toArray(), 'service_id');

        return view('lawyers.employee.settings', [
            'services' => $services,
            'user_service_ids' => $user_service_ids,
        ]);
    }

    public function actionEmployeeStore(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer|exists:' . UserEntity::class . ',id',
            'service_ids.*' => 'required|integer|exists:' . Service::class . ',id',
        ];

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $result = [];
        DB::table('user_employee_service')->where('user_id', Auth::id())->delete();

        foreach ($request->all()['service_ids'] ?? [] as $service_id) {
            $result[] = $employee_service = new EmployeeService();
            $employee_service->user_id = $request->input('user_id');
            $employee_service->service_id = $service_id;
            $employee_service->save();
        }

        return response()->json($request);
    }

    public function actionEmployeeServiceUpdate(Request $request)
    {
        $rules = [
            'is_main' => 'boolean',
            'price' => "exclude_if:is_main,'1'|required|integer",
            'description' => "exclude_if:is_main,'1'|required|string",
            'user_id' => 'required|integer|exists:' . UserEntity::class . ',id',
            'service_id' => 'required|integer|exists:' . EmployeeService::class . ',id',
        ];

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $employee_service = EmployeeService::find($request->input('service_id'));
        $employee_service->is_main = boolval($request->input('is_main'));

        if ($employee_service->is_main) {
            $employee_service->description = $request->input('description');
            $employee_service->price = $request->input('price');
        }

        $employee_service->save();

        return response()->json($employee_service);
    }
}
