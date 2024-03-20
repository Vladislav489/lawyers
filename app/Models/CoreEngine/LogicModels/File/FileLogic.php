<?php

namespace App\Models\CoreEngine\LogicModels\File;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\Another\FileSystemLogic;
use App\Models\CoreEngine\ProjectModels\File\File;

class FileLogic extends FileSystemLogic
{
    const FILE_VACANCY = 'vacancy';

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new File();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    protected function compileGroupParams(): array
    {
        $this->group_params = ['select' => [], 'by' => [], 'relatedModel' => []];

        return $this->group_params;
    }

    protected function defaultSelect(): array
    {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    public function store(array $data, string $type): array|bool {
        if (empty($data)) return false;
        $data['files'] = is_array($data['files']) ? $data['files'] : [$data['files']];
        $files = $data['files'];
        if (empty($files)) return false;
        unset($data['files']);
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $savedFileInfo = $this->saveFile($type . '/' . $data['id'], $fileName, $file);
            $savedFileInfo['user_id'] = $data['user_id'];
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
}
