<?php

namespace App\Models\CoreEngine\LogicModels\Chat;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Chat\Chat;
use App\Models\CoreEngine\ProjectModels\Chat\ChatUser;
use Illuminate\Support\Facades\DB;

class ChatLogic extends CoreEngine
{
    protected $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Chat();
        $this->query = $this->engine->newQuery();
        $this->helpEngine['chatUser'] = self::createTempLogic(new ChatUser());
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    public function getChatList() {
        $userChatIds = $this->helpEngine['chatUser']->getQuery()
            ->where('user_id', auth()->id())
            ->pluck('chat_id')
            ->toArray();

        $this->params['chat_id'] = explode(',',implode(",", $userChatIds));
        $list = (new ChatLogic($this->params))
            ->setJoin(['ChatLastMessage', 'CountNewMessages', 'ChatUser'])
            ->order('desc', 'updated_at')
            ->offPagination()
//            ->OnDebug()
            ->getList()['result'];

        foreach ($list as $index => $chat) {
            $list[$index]['chat_users'] = json_decode($chat['chat_users'], true);
            $list[$index]['last_message'] = json_decode($chat['last_message'], true);
        }


        foreach ($list as $index => $chat) {
            if ($chat['is_group'] == 0 && !str_contains($chat['name'], 'Чат заказа #')) {
                foreach ($chat['chat_users'] as $chatUser) {
                    if ($chatUser['user_id'] != auth()->id()) {
                        $list[$index]['name'] = $chatUser['name'];
                        break;
                    }
                }
                if(!empty($chat['chat_users'])) {
                    if ($this->isDeleted($chat)) {
                        unset($list[$index]);
                    }
//                    $presentUserIds = array_column($chat['chat_users'], 'user_id');
//                    if (!in_array(auth()->id(), $presentUserIds)) {
//                        unset($list[$index]);
//                    }
                }
            }
        }

        return ['result' => $list];
    }

    public function isDeleted($chat) {
        $deletedForMyself = array_filter($chat['chat_users'], function($chatUser) {
            return $chatUser['is_deleted'] == 1 && $chatUser['user_id'] == auth()->id();
        });

        return $deletedForMyself;
    }

    public function getChatAndUndeleteIt() {
        $chat = parent::getOne();
        if (empty($chat)) {
            return false;
        }
        $chat['chat_users'] = json_decode($chat['chat_users'], true);
        foreach ($chat['chat_users'] as $index => $chat_user) {
            if ($chat_user['is_deleted'] == 1) {
                $chat_user['is_deleted'] = 0;
                $chat['chat_users'][$index]['is_deleted'] = 0;
                unset($chat_user['name']);
                $this->helpEngine['chatUser']->save($chat_user);
            }
        }
        return $chat;
    }

    public function getChatInfo() {
        $chat = parent::getOne();
        $chat['chat_users'] = json_decode($chat['chat_users'], true);
        foreach ($chat['chat_users'] as $user) {
            if ($user['user_id'] != auth()->id()) {
                $chat['name'] = $user['name'];
                break;
            }
        }
        return ['result' => $chat];
    }

    public function store($data) {
        try {
            $chat = array_intersect_key(
                $data,
                array_flip($this->engine->getFillable())
            );

            if (isset($data['id'])) {
                $chat['id'] = $data['id'];
                $chat = setTimestamps($chat, 'update');
            }

            if ($data['id'] = $this->save($chat)) {
                if ($this->addChatUsers($data)) {
                    return $data;
                }
                return false;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function addChatUsers($data) {
        $data['user_ids'] = [$data['user_id'], $data['target_user_id']];
        $result = [];
        foreach ($data['user_ids'] as $user_id) {
            $chat['user_id'] = $user_id;
            $chat['chat_id'] = (string)$data['id'];
            $chatUser = array_intersect_key($chat, array_flip($this->helpEngine['chatUser']->getEngine()->getFillable()));

            $res = $this->helpEngine['chatUser']->save($chatUser);
            if ($res) {
                $result[] = $res;
            }
        }
        return !empty($result) ? $result : false;
    }



    public function deleteChat(array $data): bool
    {
        $chatUserRowId = ChatUser::where([['user_id', $data['user_id']], ['chat_id', $data['chat_id']]])->first()->id;
        return $this->helpEngine['chatUser']->delete($chatUserRowId);
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [
            [
                'field' => $tab . '.id', 'params' => 'chat_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.user_id', 'params' => 'user_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [
                'field' => $tab . '.name', 'params' => 'name',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],


        ];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    protected function compileGroupParams(): array
    {
        $userId = auth()->id();
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'ChatUser' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'is_block', is_block, 'is_deleted', is_deleted, 'is_read', is_read, 'user_id',
                                user_id, 'name', (SELECT CONCAT_WS(' ', last_name, first_name, middle_name) FROM user_entity WHERE id = user_id))) as chat_users,
                                chat_id FROM chat_user GROUP BY chat_id) as ChatUser ON ChatUser.chat_id = chat.id"),
                    'field' => ['chat_users'],
                ],
                'ChatMessage' => [
                    'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'is_read', is_read, 'created_at',
                                created_at, 'message', message, 'recipients', recipients, 'message_type_id', message_type_id,
                                'sender_user_id', sender_user_id, 'target_user_id', target_user_id, 'date', DATE(created_at), 'time',
                                 TIME_FORMAT(created_at, '%H:%i'))) as chat_messages,
                                chat_id, created_at FROM chat_message GROUP BY chat_id) as ChatMessage ON ChatMessage.chat_id = chat.id"),
                    'field' => ['chat_messages'],
                ],
                'ChatLastMessage' => [
                    'entity' => DB::raw("(SELECT
                                                JSON_OBJECT(
                                                    'message',
                                                     CASE
                                                      WHEN cm.message_type_id = 1 THEN cm.message
                                                      WHEN cm.message_type_id <> 1 THEN 'Файл'
                                                     END,
                                                    'is_read', cm.is_read,
                                                    'message_type_id', cm.message_type_id,
                                                    'last_message_time', TIME_FORMAT(cm.created_at, '%H:%i'),
                                                    'last_message_date', DATE(cm.created_at)
                                                ) AS last_message,
                                                cm.chat_id,
                                                cm.id
                                            FROM
                                                chat_message cm
                                            INNER JOIN (
                                                SELECT
                                                    chat_id,
                                                    MAX(id) AS max_id
                                                FROM
                                                    chat_message
                                                GROUP BY
                                                    chat_id
                                            ) AS latest_message ON cm.chat_id = latest_message.chat_id AND cm.id = latest_message.max_id
                                             GROUP BY cm.chat_id) as ChatLastMessage
                                             ON ChatLastMessage.chat_id = chat.id"),
                    'field' => ['last_message'],
                ],
                'CountNewMessages' => [
                    'entity' => DB::raw("(SELECT JSON_LENGTH(JSON_ARRAYAGG(
                                JSON_OBJECT('id', id))) as count_new_messages,
                                chat_id FROM chat_message WHERE sender_user_id <> $userId AND is_read = 0 GROUP BY chat_id) as CountNewMessages ON CountNewMessages.chat_id = chat.id"),
                    'field' => ['count_new_messages'],
                ],

            ]
        ];

        return $this->group_params;
    }
}
