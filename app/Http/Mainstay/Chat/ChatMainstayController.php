<?php
namespace App\Http\Mainstay\Chat;
use App\Events\MessageDeleteEvent;
use App\Models\CoreEngine\LogicModels\Chat\ChatLogic;
use App\Models\CoreEngine\LogicModels\Chat\ChatMessageLogic;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatMainstayController extends MainstayController
{
    public function createChat($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return (new ChatLogic())->store($this->params);
    }

    public function actionGetChat($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = $this->params;
        if ($data['user_id'] == $data['target_user_id']) {
            return response()->json(['message' => "forbidden"], 403);
        }
        if ($data['user_id'] == null) {
            return response()->json(['message' => "unauthorized"], 401);
        }
        if (!isset($data['name'])) {
            $data['name'] = (new UserLogic(
                ['id' => $data['target_user_id']],
                [DB::raw("CONCAT_WS(' ', last_name, first_name, middle_name) as name")]))
                ->getOne()['name'];
        }
        $chat = (new ChatLogic($data))->setJoin(['ChatUser'])->getChatAndUndeleteIt();
        if (!$chat) {
            $chat = $this->createChat($data);
        }
        return response()->json($chat);

    }

    public function actionGetChatList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new ChatLogic())->getChatList());
    }

    public function actionGetChatInfo($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        if ($this->params['id'] == null) {
            return false;
        }
        $data = Validator::validate($this->params, [
            'id' => 'required|integer|exists:chat,id'
        ]);
        return response()->json((new ChatLogic($data))->setJoin(['ChatUser'])->getChatInfo());
    }

    public function actionGetChatMessages($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        if ($this->params['chat_id'] == null) {
            return false;
        }
        $data = Validator::validate($this->params, [
            'chat_id' => 'required|integer|exists:chat,id',
            'page' => 'required',
            'pageSize' => 'required'
        ]);
        $select = ['*', DB::raw("DATE_FORMAT(created_at, '%H:%i') as time"),];
        return response()->json((new ChatMessageLogic($data, $select))->setJoin(['Sender'])->order('desc', 'id')->getMessageList());
    }

    public function actionSendMessage($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new ChatMessageLogic())->store($this->params));
    }

    public function actionReadMessage($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new ChatMessageLogic())->read($this->params));
    }

    public function actionDeleteMessage($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = Validator::validate($this->params, [
            'id' => 'required|integer|exists:chat_message,id',
            'user_id' => 'required|integer|exists:user_entity,id',
            'chat_id' => 'required|integer|exists:chat,id',
            'recipients' => 'required'
        ]);
        if ($data['user_id'] !== (string) auth()->id()) {
            return false;
        }
        $response = (new ChatMessageLogic())->deleteForeva($data['id']);
        if ($response > 0) {
            $data['recipients_arr'] = json_decode($data['recipients'], true);
            broadcast(new MessageDeleteEvent($data));
            return response()->json($response);
        }
        return response()->json(false);
    }

    public function actionUpdateMessage($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = Validator::validate($this->params, [
            'id' => 'required|integer|exists:chat_message,id',
            'message' => 'required|string'
        ]);
        return response()->json((new ChatMessageLogic())->save($data));
    }

    public function actionChatDelete($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $data = Validator::validate($this->params, [
            'chat_id' => 'required|integer|exists:chat,id'
        ]);
        $data['user_id'] = \auth()->id();
        return response()->json((new ChatLogic())->deleteChat($data));
    }
}
