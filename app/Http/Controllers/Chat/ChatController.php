<?php

namespace App\Http\Controllers\Chat;

use App\Models\System\ControllersModel\FrontController;
use Illuminate\Support\Facades\Auth;

class ChatController extends FrontController
{
    public function getPageParams(): array
    {
        return [
            'actionChatList' => [
                'name' => 'Chat list',
                'template' => 'lawyers.chat.chat-list'
            ],
            'actionChatCreate' => [
                'name' => 'Chat create',
                'template' => 'lawyers.chat.chat-create'
            ],
            'actionChatEdit' => [
                'name' => 'Chat edit',
                'template' => 'lawyers.chat.chat-edit'
            ],
        ];
    }

    // TODO
    public function callAction($method, $parameters)
    {
        if (true) {
            // return response()->json(['message' => 'forbidden']);
        }

        return parent::callAction($method, $parameters);
    }

    public function actionChatList()
    {
        return view('lawyers.chat.chat-list');
    }

    public function actionChatCreate()
    {
        return view('lawyers.chat.chat-create');
    }

    public function actionChatEdit()
    {
        return view('lawyers.chat.chat-edit');
    }
}
