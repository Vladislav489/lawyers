<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeAchievement;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeePhoto;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\Country;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeLogic extends UserLogic
{
    protected $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new UserEntity();
        $this->params = array_merge($params, ['type_id' => '2']);
        $this->query = $this->engine->newQuery();
        $this->helpEngine['employee'] = self::createTempLogic(new Employee());
        $this->helpEngine['achievement'] = self::createTempLogic(new EmployeeAchievement());
        $this->helpEngine['photo'] = self::createTempLogic(new EmployeePhoto());
        $this->helpEngine['service'] = self::createTempLogic(new EmployeeService());
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($this->params, $select);
    }

    protected function defaultSelect(): array {
        $tab = $this->engine->getTable();
        $this->default = [];

        return $this->default;
    }

    public function save($data) {
        if (empty($data)) return false;
        DB::beginTransaction();
        $data['modifier_id'] = 1;
        $data = parent::save($data);
        $data['user_id'] = empty($data['id']) ? auth()->id() : $data['id'];
        unset($data['id']);
        if (!isset($data['avatar_path']) && isset($data['avatar'])) {
            $data['avatar_path'] = $this->storeImage($data['avatar'], 'avatar', $data['user_id']);
        }
        $employee = array_intersect_key($data, array_flip($this->helpEngine['employee']->getEngine()->getFillable()));
        $employeeRecord = $this->helpEngine['employee']->getEngine()->select('id')->where('user_id', $data['user_id'])->first();
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
            $employeeForUpdate = array_intersect_key($data, array_flip($this->helpEngine['employee']->getEngine()->getFillable()));
            $data['employee_id'] = $this->helpEngine['employee']->update($employeeForUpdate, $employeeRecord->id);
            $hasAchievementsToSave = isset($data['achievements']);
            $hasPhotosToSave = isset($data['photos']);
            if (!$hasAchievementsToSave && !$hasPhotosToSave) {
                DB::commit();
                return $data;
            }
            if ($hasAchievementsToSave && !$hasPhotosToSave) {
                if ($this->saveAchievements($data)) {
                    DB::commit();
                    return $data;
                }
            }
            if (!$hasAchievementsToSave && $hasPhotosToSave) {
                if ($this->savePhotos($data)) {
                    DB::commit();
                    return $data;
                }
            }
            if ($hasAchievementsToSave && $hasPhotosToSave) {
                if ($this->saveAchievements($data) && $this->savePhotos($data)) {
                    DB::commit();
                    return $data;
                }
            }

        }
        DB::rollBack();
        return false;
    }

    public function storeImage($image, $type, $userId) {
        if (is_string($image)) {
            return $this->storeImageBase64($image, $type, $userId);
        } else {
            try {
                $image_path = '/' . $userId . '/' . $type . '/' . md5($image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('/employee', $image, $image_path);
                return '/employee' . $image_path;
            } catch (\Throwable $e) {
                return false;
            }
        }
    }

    public function storeImageBase64($imageBase64, $type, $userId) {
        $imageInfo = $this->prepareImageBase64($imageBase64);
        $image = base64_decode($imageInfo['image']);
        $image_path = '/' . $userId . '/'. $type . '/' . uniqid() . '.' . $imageInfo['extension'];
        try {
            Storage::disk('public')->put('/employee' . $image_path, $image);
            return '/employee' . $image_path;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function prepareImageBase64($base64) {
        $imageParts = explode(";base64,", $base64);
        $imageTypeArr = explode("image/", $imageParts[0]);
        $imageType = $imageTypeArr[1];
        $imageBase64 = $imageParts[1];
        return [
            'extension' => $imageType,
            'image' => $imageBase64
        ];

    }

    public function saveAchievements(array $data) {
        if (empty($data)) return false;
        if (!empty($data['achievements']) && $data['user_id']) {
            foreach ($data['achievements'] as $achievement) {
                $achievementData['path'] = $this->storeImage($achievement, 'achievement', $data['user_id']);
                $achievementData['user_id'] = $data['user_id'];
                $achievementIds[] = $this->helpEngine['achievement']->insert($achievementData);
            }
        }
        return !empty($achievementIds);
    }

    public function savePhotos(array $data) {
        if (empty($data)) return false;
        if (!empty($data['photos']) && $data['employee_id']) {
            foreach ($data['photos'] as $photo) {
                $photoData['path'] = $this->storeImage($photo, 'photo', $data['user_id']);
                $photoData['employee_id'] = $data['employee_id'];
                $photoIds[] = $this->helpEngine['photo']->insert($photoData);
            }
        }
        return !empty($photoIds);
    }

    public function getImage($type, $id) {
        $query = $this->helpEngine[$type]->offPagination();
        $query->getQueryLink()->select('path')->where('id', $id);
        $result = $query->getOne();
        return !empty($result) ? $result : false;
    }

    public function imageDeleteFromDB($type, $id) {
        $query = $this->helpEngine[$type];
        return $query->deleteForeva($id);
    }


    public function deleteImage(array $data) {
        $hasImage = !empty($data['photo_id']) || !empty($data['achievement_id']);
        if (empty($data) || !$hasImage) {
            return false;
        }
        $userId = $data['user_id'];
        $isDeleteAllowed = $userId == auth()->id();
        $type = !empty($data['photo_id']) ? 'photo' : 'achievement';
        if ($isDeleteAllowed) {
            $id = $data[$type . '_id'];
            $image = $this->getImage($type, $id);
            if ($this->imageDeleteFromDB($type, $id)) {
                Storage::disk('public')->delete($image['path']);
                return true;
            }
        }
        return false;
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
                'Photos' => [
                    'entity' => new EmployeePhoto(),
                    'relationship' => ['employee_id', 'Employee.id'],
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
                    'field' => [],
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
                    'field' => [],
                ],
                'City' => [
                    'entity' => new City(),
                    'relationship' => ['id', 'city_id'],
                    'field' => ['*'],
                ],
                'Country' => [
                    'entity' => new Country(),
                    'relationship' => ['id', 'country_id'],
                    'field' => ['*'],
                ]
            ]
        ];
        return $this->group_params;
    }
}
