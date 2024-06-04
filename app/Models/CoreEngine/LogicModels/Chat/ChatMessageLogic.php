<?php

namespace App\Models\CoreEngine\LogicModels\Chat;

use App\Events\NewMessageNotificationEvent;
use App\Events\ReadMessageEvent;
use App\Events\SendMessageEvent;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\File\FileLogic;
use App\Models\CoreEngine\ProjectModels\Chat\Chat;
use App\Models\CoreEngine\ProjectModels\Chat\ChatMessage;
use App\Models\CoreEngine\ProjectModels\Chat\ChatUser;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class ChatMessageLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new ChatMessage();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    public function getMessageList() {
        $list = parent::getList();
        $messages = $list['result'];
        unset($list['result']);
        $list['result']['chat_messages'] = array_reverse($messages);
        $list['result']['auth_user'] = auth()->id();
        return $list;
    }

    public function read($data) {
        if (!isset($data['id']) || !isset($data['chat_id'])) {
            return false;
        }
        $message['id'] = $data['id'];
        $message['is_read'] = $data['is_read'];
        if ($this->save($message)) {
            broadcast((new ReadMessageEvent($data)))->toOthers();
            return $data;
        }
        return false;
    }

    public function store($data) {
        if (isset($data['recipients'])) {
            $data['recipients_arr'] = json_decode($data['recipients'], true);
        }
        if (isset($data['files'])) {
            $data['user_id'] = auth()->id();
            $extension = $data['files']->getClientOriginalExtension();
            if (in_array($extension, ['gif', 'jpg', 'jpeg', 'png', 'bmp'])) {
                $data['file_type'] = FileLogic::TYPE_CHAT_IMG;
            } else {
                $data['file_type'] = FileLogic::TYPE_CHAT_FILE;
            }

            $fileInfo = (new FileLogic())->store($data, FileLogic::FILE_CHAT);
            $data['message'] = $fileInfo['files'][0]['path'];
        }
        try {
            $message = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );
            if (isset($data['id'])) {
                $message['id'] = $data['id'];
                $message = setTimestamps($message, 'update');
            }

            if ($data['id'] = $this->save($message)) {
                $chat['id'] = $data['chat_id'];
                $chat = setTimestamps($chat, 'update');
                if (!(new ChatLogic())->save($chat)) {
                    return false;
                }
                broadcast((new SendMessageEvent($data)))->toOthers();
                broadcast((new NewMessageNotificationEvent($data)));

                return [
                    'message' => $data['message'],
                    'chat_id' => $data['chat_id'],
                    'sender_user_id' => $data['sender_user_id'],
                    'id' => $data['id'],
                    'message_type_id' => $data['message_type_id'],
                    'recipients' => $data['recipients'],
                    'time' => Carbon::now()->format('H:i'),
                    'is_read' => 0
                ];
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function getAllChatUsers($chatId) {
        return ChatUser::where([['user_id', '!=', auth()->id()], ['chat_id', $chatId], ['is_deleted', 0], ['is_block', 0]])->pluck('user_id')->toArray();
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [
            [
                'field' => $tab . '.id', 'params' => 'message_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.chat_id', 'params' => 'chat_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.message_type_id', 'params' => 'message_type_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.sender_user_id', 'params' => 'sender_user_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.target_user_id', 'params' => 'target_user_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.is_read', 'params' => 'is_read',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],

        ];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Chat' => [
                    'entity' => new Chat(),
                    'relationship' => ['id', 'chat_id'],
                    'field' => [],
                ],
                'ChatUser' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('is_block', is_block, 'is_read', is_read, 'user_id',
                                user_id)) as chat_users,
                                chat_id FROM chat_user GROUP BY chat_id) as ChatUser ON ChatUser.chat_id = chat_message.chat_id"),
                    'field' => ['chat_users'],
                ],
                'Sender' => [
                    'entity' => DB::raw("(SELECT CONCAT_WS(' ', last_name, first_name, middle_name) as sender_name, id
                    FROM user_entity) AS Sender ON Sender.id = chat_message.sender_user_id"),
                    'field' => ['sender_name'],
                ]
            ]
        ];

        return $this->group_params;
    }
}
