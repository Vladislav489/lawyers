<?php


namespace App\Http\Mainstay\Employee;


use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (!Auth::check() || Auth::user()->type->name !== 'employee') {
            return Response::json(["message" => "нет прав"]);
        }
        return parent::callAction($method, $parameters);
    }

    public function actionEmployeeStore(Request $request)
    {

        // такой подход делает два лишних запроса!!!!!!!
        $rules = [
            'user_id' => 'required|integer|exists:' . UserEntity::class . ',id',
            'service_ids.*' => 'required|integer|exists:' . Service::class . ',id',
        ];

        if (($validator = Validator::make($request->all(), $rules))->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }
        // у тебя должна быть логическая модель
        $result = [];
        DB::table('user_employee_service')->where('user_id', Auth::id())->delete();

        // данный подход плох куча запросов можно сделать одним
        // у модели есть CoreEngine    public function insert($data,$type = self::INSERT)    const INSERT_MULTI = 3;
        // получить данные можно  через getList   если создашь правло фильтрации по user_id в модели логики
        foreach ($request->all()['service_ids'] ?? [] as $service_id) {
            $result[] = $employee_service = new EmployeeService();
            $employee_service->user_id = $request->input('user_id');
            $employee_service->service_id = $service_id;
            $employee_service->save();
        }
        //пезюмирую   в итогк сдесь от 4 запросов до бесконечности  максимально должно быть 2-3! 3 если надо вернуть список данных
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
        //если нужна частичное обновление
        /* если ты создал сорви для юриста то ты должен знать его id
        для редактирования достаточно id
        if(isset($data['id']) && !empty($data['id']) && is_numeric($data['id'])) {
            $id = $data['id']
            unset($data['id']);
            (new  EmployeeServiceLogic())->update($data,$id);
        }
        я не делаю лишний запрос и обновляю только нужную часть


        тут не понятная логика    Надо обсуждать 
       */
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
