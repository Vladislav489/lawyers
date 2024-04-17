<?php
namespace App\Http\Mainstay\Chat;
use App\Models\CoreEngine\LogicModels\Chat\ChatLogic;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Chat\Chat;
use App\Models\System\Admin\Rule;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatMainstayController extends MainstayController
{
    public function callAction($method, $parameters)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionCreateChat($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $rules = [
            'id' => 'nullable|integer|exists:chat,id',
            'owner_user_id' => 'required|integer|exists:user_entity,id',
            'is_group' => 'nullable|integer',
            'name' => 'nullable|string',
            'recipient_id' => 'required|integer|exists:user_entity,id'
        ];
        $data = Validator::validate($this->params, $rules);
        if (!isset($data['is_group']) || $data['is_group'] == 0) {
            $data['name'] = (new UserLogic(['id' => (string) $data['recipient_id']]))->getUserName();
        }
        $chatId = (new ChatLogic())->store($data);
//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'errors' => $validator->errors()
//            ]);
//        }
//
//        $request->merge([
//            'user_id' => Auth::id(),
//        ]);
//
//        return response()->json(
//            (new ChatLogic())->store($request->all())
//        );
    }

    public function actionGetChat(Request $request)
    {
        return response()->json(
            Chat::find($request->input('id'))
        );
    }

    public function actionGetChatList()
    {
        return response()->json((new ChatLogic())->getList());
    }

    public function actionChatDelete(Request $request)
    {
        if ($request->isMethod('delete')) {
            return response()->json(
                (new ChatLogic())->deleteChat($request->all())
            );
        }
    }
}
