<?php

namespace App\Models\CoreEngine\LogicModels\Chat;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Chat\Chat;
use App\Models\CoreEngine\ProjectModels\Chat\ChatUser;

class ChatLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Chat();
        $this->query = $this->engine->newQuery();
        $this->helpEngine['chatUser'] = self::createTempLogic(new ChatUser());
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }
    protected function defaultSelect(): array
    {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
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
                return $data;
            }

        } catch (\Throwable $e) {
        }

        return false;
    }

    public function addUserToChat($data) {

    }



    public function deleteChat(array $data): bool
    {
        try {
            $chat = $this->setModel(new Chat());
            return $chat->delete($data['id']);
        } catch (\Throwable $e) {
        }

        return false;
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    protected function compileGroupParams(): array
    {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => []
        ];

        return $this->group_params;
    }
}
