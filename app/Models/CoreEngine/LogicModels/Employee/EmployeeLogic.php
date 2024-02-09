<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeAchievement;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeLogic extends UserLogic
{
    protected $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new UserEntity();
        $this->helpEngine['employee'] = self::createTempLogic(new Employee());
        $this->helpEngine['achievement'] = self::createTempLogic(new EmployeeAchievement());
        $this->query = $this->engine->newQuery();
        $this->params = array_merge($params, ['type_id' => '2']);
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($this->params, $select);
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
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
            return '/employee' . $image_path;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function saveAchievements(array $data)
    {
        if (empty($data)) return false;
        if (!empty($data['achievements']) && $data['user_id']) {
            foreach ($data['achievements'] as $achievement) {
                $achievementData['path'] = $this->storeImage($achievement, 'achievement', $data['user_id']);
                $achievementData['user_id'] = $data['user_id'];
                $achievementIds[] = $this->helpEngine['achievement']->insert($achievementData);
            }
            dd($achievementIds);
        }
        return !empty($achievementIds);
    }

    public function deleteImage($path) {
        return Storage::disk('public')->delete($path);
    }

    protected function getFilter(): array {
        $tab = $this->engine->getTable();
        $this->filter = [
            [   'field' => $tab.'.id','params' => 'user_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab.'.type_id','params' => 'type_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => 'Employee.is_confirmed','params' => 'is_confirmed',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'Employee.dt_practice_start','params' => 'start_practice_date',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'Employee.consultation_price','params' => 'consultation_price',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => 'Company.name','params' => 'company',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            [   'field' => $tab .'.country_id','params' => 'country_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => $tab .'.city_id','params' => 'city_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => 'InnerJoinService.service_id','params' => 'service_id',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
                'relatedModel' => 'InnerJoinService'
            ],
            [   'field' => "CONCAT(user_entity.first_name, ' ', user_entity.last_name)",'params' => 'search_spec',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
            ],
            [   'field' => "TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW()))",'params' => 'experience',
                'validate' => ['string' => true,"empty" => true],
                'type' => 'string|array',
                "action" => '>=', 'concat' => 'AND',
            ],
//            [   'field' => "",'params' => 'rating',
//                'validate' => ['string' => true,"empty" => true],
//                'type' => 'string|array',
//                "action" => '>=', 'concat' => 'AND',
//            ],
//            [   'field' => "",'params' => 'evaluation',
//                'validate' => ['string' => true,"empty" => true],
//                'type' => 'string|array',
//                "action" => '>=', 'concat' => 'AND',
//            ],
        ];

        return $this->filter = array_merge($this->filter, parent::getFilter());
    }

    protected function compileGroupParams(): array {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => [
                'Employee' => [
                    'entity' => new Employee(),
                    'relationship' => ['user_id', 'id'],
                    'field' => ['*'],
                ],
                'Company' => [
                    'entity' => new Company(),
                    'relationship' => ['Employee.company_id', 'company_id'],
                    'field' => ['*'],
                ],
                'EmployeeService' => [
                    'entity' => new EmployeeService(),
                    'relationship' => ['user_id', 'id'],
                    'field' => ['*'],
                ],
                'Service' => [
                    'entity' => DB::raw((new Service())->getTable() . ' as Service ON EmployeeService.service_id = Service.id'),
                    'field' => [],
                ],
                'InnerJoinService' => [
                    'entity' => new EmployeeService(),
                    'relationship' => ['user_id', 'id'],
                    'field' => [],
                    'type' => 'inner'
                ],
                'Achievements' => [
                    'entity' => new EmployeeAchievement(),
                    'relationship' => ['user_id', 'id'],
                    'field' => ['path'],
                ]
            ]
        ];
        return $this->group_params;
    }
}
