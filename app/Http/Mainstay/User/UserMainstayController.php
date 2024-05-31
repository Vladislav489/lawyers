<?php

namespace App\Http\Mainstay\User;

use App\Models\CoreEngine\LogicModels\Notification\NotificationLogic;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserMainstayController extends MainstayController {

    public function actionGetNotificationsCount($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = Validator::validate($this->params, [
            'user_id' => 'required|integer|exists:user_entity,id'
        ]);
        $data['is_read'] = '0';
        return response()->json(['result' => (new NotificationLogic($data))->getTotal()]);
    }

    public function actionGetNotifications($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = Validator::validate($this->params, [
            'user_id' => 'required|integer|exists:user_entity,id'
        ]);
        $select = ['id', 'message', 'is_read', DB::raw("DATE_FORMAT(created_at, '%d-%c-%y %H:%i') as date"),];
        return response()->json((new NotificationLogic($data, $select))->order('desc', 'created_at')->setLimit(5)->getList());
    }

    public function actionReadNotification($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = Validator::validate($this->params, [
            'id' => 'required|integer|exists:notifications,id',
        ]);
        $data['is_read'] = '1';
        return response()->json((new NotificationLogic())->store($data));
    }
}

