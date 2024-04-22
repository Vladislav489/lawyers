<?php

namespace App\Models\CoreEngine\LogicModels\File;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\Another\FileSystemLogic;
use App\Models\CoreEngine\ProjectModels\File\File;

class FileLogic extends FileSystemLogic
{
    const FILE_VACANCY = 'vacancy';

    const TYPE_VACANCY = 1;
    const TYPE_VACANCY_CLOSING = 2;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->params = $params;
        $this->engine = new File();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    public function store(array $data, string $type): array|bool {
        if (empty($data)) return false;
        if (empty($data['files'])) return $data;
        $data['files'] = is_array($data['files']) ? $data['files'] : [$data['files']];
        $files = $data['files'];
        unset($data['files']);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            if (!isset($data['id'])) {
                $data['id'] = $data['vacancy_id'];
            }

            if (!isset($data['user_id'])) {
                $data['user_id'] = $data['employee_user_id'];
            }

            $savedFileInfo = $this->saveFile($type . '/' . $data['id'], $fileName, $file);
            $savedFileInfo['user_id'] = $data['user_id'];
            $savedFileInfo['vacancy_id'] = $data['id'];
            $savedFileInfo['type'] = $data['file_type'];
            $savedFileInfo['name'] = $savedFileInfo['fileName'];
            $fileRecord = array_intersect_key($savedFileInfo, array_flip($this->engine->getFillable()));

            if (isset($fileRecord['id'])) {
                $fileRecord['id'] = $savedFileInfo['id'];
            }

            if ($savedFileInfo['id'] = $this->save($fileRecord)) {
                $data['files'][] = $savedFileInfo;
            }
        }
        return $data;
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.name','params' => 'name',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.path','params' => 'path_start',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'LIKE%', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.path','params' => 'path',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.user_id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.type','params' => 'type',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
        ];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    protected function compileGroupParams(): array
    {
        $this->group_params = ['select' => [], 'by' => [], 'relatedModel' => []];

        return $this->group_params;
    }
}
