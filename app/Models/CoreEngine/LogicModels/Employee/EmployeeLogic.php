<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new Employee();
        $this->query = $this->engine->newQuery();
        $this->params = $params;
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => []
        ];

        return $this->group_params;
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }

    public function storeEmployee(array $data): array|bool {
        $data['modifier_id'] = 1;
        $data['password'] = Hash::make($data['password']);
        $employeeData = (new UserLogic())->storeEntity($data);
        $employeeData['user_id'] = $employeeData['id'];
        unset($employeeData['id']);
        $employeeData['avatar_path'] = $this->storeImage($data['avatar'], 'avatar', $employeeData['user_id']);
        unset($employeeData['avatar']);
        if ($employee = $this->storeEntity($employeeData)) {
            if (isset($data['achievements'])) {
                foreach ($data['achievements'] as $achievement) {
                    $achievementData['path'] = $this->storeImage($achievement, 'achievement', $employeeData['user_id']);
                    $achievementData['employee_id'] = $employee['id'];
                    ((new AchievementLogic())->storeEntity($achievementData)) ?: $this->deleteImage($achievementData['path']);
                }
            }
            return $employee;
        } else {
            $this->deleteImage($employeeData['avatar_path']);
            $this->query->where('id', '=', $employeeData['user_id'])->delete();
        }
        return false;
    }

    public function storeImage($image, $type, $userId) {
        $image_path = '/' . $userId . '/'. $type . '/' . md5($image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        try {
            Storage::disk('public')->putFileAs('/employee', $image, $image_path);
            return $image_path;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function deleteImage($path) {
        return Storage::delete($path);
    }
}
