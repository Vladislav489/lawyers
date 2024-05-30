<?php

namespace App\Models\CoreEngine\LogicModels\Notification;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Notification\Notification;

class NotificationLogic extends CoreEngine {
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new Notification();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params, $select);
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    public function store($data) {
        $notification = array_intersect_key($data, array_flip($this->engine->getFillable()));
        if (isset($data['id'])) {
            $notification['id'] = $data['id'];
        }
        if ($data['id'] = $this->save($notification)) {
            return $data;
        }
        return false;
    }

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.is_read','params' => 'is_read',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.user_id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
        ];

        return $this->filter = array_merge($this->filter, parent::getFilter());;
    }

    protected function compileGroupParams() {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => []
        ];
        return $this->group_params;
    }
}
