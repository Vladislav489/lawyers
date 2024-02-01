<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeAchievement;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeLogic extends UserLogic
{
    protected $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new UserEntity();
        $this->helpEngine['employee'] = self::createTempLogic(new Employee());
        $this->helpEngine['achievement'] = self::createTempLogic(new EmployeeAchievement());
        $this->query = $this->engine->newQuery();
        $this->params = $params;
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
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

    public function save($data) {
        if (empty($data)) return false;
        DB::beginTransaction();
        $data['modifier_id'] = 1;
        $data = parent::save($data);
        $data['user_id'] = $data['id'];
        $data['avatar_path'] = $this->storeImage($data['avatar'], 'avatar', $data['user_id']);
        $employee = array_intersect_key($data, array_flip($this->helpEngine['employee']->getEngine()->getFillable()));
        $employeeRecord = $this->helpEngine['employee']->getEngine()->where('user_id', $data['user_id'])->first('id');
        if ($data['user_id'] && !$employeeRecord) {
            $data['employee_id'] = $this->helpEngine['employee']->insert($employee);
            if (!isset($data['employee_id'])) {$this->deleteImage($data['avatar_path']);}
            if (empty($data['achievements'])) {
                DB::commit();
                return $data;
            } else {
                if ($this->saveAchievements($data)) {
                    DB::commit();
                    return $data;
                }
            }
        } elseif ($data['user_id'] && $employeeRecord) {
            $data['employee_id'] = $this->update($employee, $data['user_id']);
            if (!isset($data['achievements'])) {
                DB::commit();
                return $data;
            } else {
                if ($this->saveAchievements($data)) {
                    DB::commit();
                    return $data;
                }
            }
        }
        DB::rollBack();
        return false;
    }

    public function storeImage($image, $type, $userId) {
        $image_path = '/' . $userId . '/'. $type . '/' . md5($image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        try {
            Storage::disk('public')->putFileAs('/employee', $image, $image_path);
            return '/public/employee' . $image_path;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function saveAchievements(array $data)
    {
        if (empty($data)) return false;
        if (isset($data['achievements']) && !empty($data['achievements']) && $data['employee_id']) {
            foreach ($data['achievements'] as $achievement) {
                $achievementData['path'] = $this->storeImage($achievement, 'achievement', $data['user_id']);
                $achievementData['employee_id'] = $data['employee_id'];
                $achievementIds = $this->helpEngine['achievement']->insert($achievementData);
            }
        }
        return !empty($achievementIds);
    }

    public function deleteImage($path) {
        return Storage::delete($path);
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.id','params' => 'id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_deleted','params' => 'is_deleted',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_confirmed','params' => 'is_confirmed',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.is_confirmed','params' => 'is_confirmed',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.dt_practice_start','params' => 'start_practice_date',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.consultation_price','params' => 'consultation_price',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'Company.name','params' => 'company',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
        ];

        return $this->filter = array_merge($this->filter, parent::getFilter());
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'User' => [
                    'entity' => new UserEntity(),
                    'relationship' => ['id', 'user_id'],
                    'field' => ['*'],
                ],
                'Company' => [
                    'entity' => new Company(),
                    'relationship' => ['id', 'company_id'],
                    'field' => ['*'],
                ],
            ]
        ];
        return $this->group_params;
    }
}
