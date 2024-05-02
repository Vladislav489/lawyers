<?php

namespace App\Models\CoreEngine\LogicModels\Employee;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use App\Models\CoreEngine\LogicModels\Vacancy\VacancyOfferLogic;
use App\Models\CoreEngine\ProjectModels\Company\Company;
use App\Models\CoreEngine\ProjectModels\Employee\Employee;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeAchievement;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeOfferResponse;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeePhoto;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeService;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeSpecialization;
use App\Models\CoreEngine\ProjectModels\Employee\EmployeeWorkingSchedule;
use App\Models\CoreEngine\ProjectModels\HelpData\City;
use App\Models\CoreEngine\ProjectModels\HelpData\DayOfWeek;
use App\Models\CoreEngine\ProjectModels\HelpData\Region;
use App\Models\CoreEngine\ProjectModels\Service\Service;
use App\Models\CoreEngine\ProjectModels\User\UserEntity;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\CoreEngine\ProjectModels\Vacancy\VacancyOffer;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Composer\Autoload\includeFile;

class EmployeeLogic extends UserLogic
{
    protected $helpEngine;

    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new UserEntity();
        $this->params = array_merge($params, ['type_id' => '2']);
        $this->query = $this->engine->newQuery();
        $this->helpEngine['employee'] = self::createTempLogic(new Employee());
        $this->helpEngine['achievement'] = self::createTempLogic(new EmployeeAchievement());
        $this->helpEngine['photo'] = self::createTempLogic(new EmployeePhoto());
        $this->helpEngine['service'] = self::createTempLogic(new EmployeeService());
        $this->helpEngine['offer_response'] = self::createTempLogic(new EmployeeOfferResponse());
        $this->helpEngine['vacancy_offer'] = self::createTempLogic(new VacancyOffer());
        $this->helpEngine['working_schedule'] = self::createTempLogic(new EmployeeWorkingSchedule());
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($this->params, $select);
    }

    protected function defaultSelect(): array
    {
        $tab = $this->engine->getTable();
        $this->default = [];

        return $this->default;
    }

    public function getEmployeeAchievements($data) {
        $res = (new EmployeeLogic($data))->setJoin(['Achievements'])->getOne();
//        dd($res);
        if (isset($res['achievements'])) {
            $res['achievements'] = json_decode($res['achievements'], true);
        }
        return $res;
    }

    public function getOne() {
        $result = parent::getOne();
        if (isset($result['photos'])) {
            $result['photos'] = json_decode($result['photos'], true);
        }
        if (isset($result['achievements'])) {
            $result['achievements'] = json_decode($result['achievements'], true);
        }
        if (isset($result['schedule'])) {
            $result['schedule'] = json_decode($result['schedule'], true);
            $result['work_time'] = $result['schedule'][0]['time_from'] . '-' . $result['schedule'][0]['time_to'];
            $result['working_days_interval'] = $result['schedule'][0]['day_of_week'] . '-' . $result['schedule'][count($result['schedule']) - 1]['day_of_week'];
        } else {
            $result['work_time'] = '';
            $result['working_days_interval'] = 'Круглосуточно';
        }
        if (isset($result['specialization'])) {
            $result['specialization'] = json_decode($result['specialization'], true);
            $result['specialization'] = Arr::pluck($result['specialization'], 'service_id');
        }
//        if (isset($result['location_coordinates'])) {
//            $result['location_coordinates'] = json_decode($result['location_coordinates'], true);
////            dd($result['location_coordinates']);
//        }
        return $result;
    }

    public function getList() {
        $result = parent::getList();
        foreach ($result['result'] as $k => $v) {
            if (isset($result['result'][$k]['specialization'])) {
                $result['result'][$k]['specialization'] = json_decode($result['result'][$k]['specialization'], true);
            }
        }
        return $result;
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
            if (!isset($data['employee_id'])) {
                $this->deleteImage($data['avatar_path']);
            }
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

    public function updateEmployeeInfo($data)
    {
        if (isset($data['site_url'])) {
            if (count(explode('://', $data['site_url'])) == 1) {
                $data['site_url'] = 'https://' . $data['site_url'];
            }
        }
        if (!$this->save($data)) {
            return false;
        }
        if (isset($data['working_days'])) {
            $this->saveWorkingSchedule($data);
        }
        if (isset($data['cert_description']) && isset($data['cert_file'])) {
            $this->saveAchievements($data);
        }
        return false;
    }

    public function saveWorkingSchedule($data)
    {
        $data['user_id'] = auth()->id();

        // удаляем предыдущее расписание
        EmployeeWorkingSchedule::where('user_id', $data['user_id'])->delete();

        if (!empty($data['working_days'])) {
            $data['time_from'] = Carbon::createFromFormat('H', $data['time_from'])->format('H:i:s');
            $data['time_to'] = Carbon::createFromFormat('H', $data['time_to'])->format('H:i:s');

            $scheduleRecordId = [];
            $workingDays = $data['working_days'];
            foreach ($workingDays as $workingDay) {
                $data['day_of_week'] = $workingDay;
                $workingDayRow = array_intersect_key($data, array_flip($this->helpEngine['working_schedule']->getEngine()->getFillable()));
                $scheduleRecordId[] = $this->helpEngine['working_schedule']->save($workingDayRow);
            }

            if (!empty($scheduleRecordId)) {
                return $data;
            }
            return false;
        }
        return false;
    }

    public function storeImage($image, $type, $userId)
    {
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

    public function storeImageBase64($imageBase64, $type, $userId)
    {
        $imageInfo = $this->prepareImageBase64($imageBase64);
        $image = base64_decode($imageInfo['image']);
        $image_path = '/' . $userId . '/' . $type . '/' . uniqid() . '.' . $imageInfo['extension'];
        try {
            Storage::disk('public')->put('/employee' . $image_path, $image);
            return '/employee' . $image_path;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function prepareImageBase64($base64)
    {
        $imageParts = explode(";base64,", $base64);
        $imageTypeArr = explode("image/", $imageParts[0]);
        $imageType = $imageTypeArr[1];
        $imageBase64 = $imageParts[1];
        return ['extension' => $imageType, 'image' => $imageBase64];

    }

    public function saveAchievements(array $data)
    {
        if (empty($data)) return false;
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        if (isset($data['cert_id'])) {
            $achievementData['id'] = $data['cert_id'];
        }
        if (isset($data['cert_file'])) {
            $achievementData['path'] = $this->storeImage($data['cert_file'], 'achievement', $data['user_id']);
        }
        $achievementData['user_id'] = $data['user_id'];
        $achievementData['description'] = $data['cert_description'];
        return $this->helpEngine['achievement']->save($achievementData);
    }

    public function savePhotos(array $data)
    {
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

    public function getImage($type, $id)
    {
        $query = $this->helpEngine[$type]->offPagination();
        $query->getQueryLink()->select('path')->where('id', $id);
        $result = $query->getOne();
        return !empty($result) ? $result : false;
    }

    public function imageDeleteFromDB($type, $id)
    {
        $query = $this->helpEngine[$type];
        return $query->deleteForeva($id);
    }


    public function deleteImage(array $data)
    {
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

    public function respondToVacancy(array $data)
    {
        if (empty($data)) return false;

        $offerResponse = array_intersect_key($data, array_flip($this->helpEngine['offer_response']->getEngine()->getFillable()));

        if (!is_null($data['employee_response_id'])) {
            $offerResponse['id'] = $data['employee_response_id'];
            $offerResponse = setTimestamps($offerResponse, 'update');
        } else {
            $offerResponse = setTimestamps($offerResponse, 'create');
        }
        $responseId = $this->helpEngine['offer_response']->save($offerResponse);
        if ($responseId) {
            $data['employee_response_id'] = $responseId;
            $data['employee_user_id'] = $data['user_id'];
            $vacancyOffer = array_intersect_key($data, array_flip($this->helpEngine['vacancy_offer']->getEngine()->getFillable()));

            if (!is_null($data['offer_id'])) {
                $vacancyOffer['id'] = $data['offer_id'];
                $vacancyOffer = setTimestamps($vacancyOffer, 'update');
            }

            if ($this->helpEngine['vacancy_offer']->save($vacancyOffer)) {
                return $this->getMyResponse(['vacancy_id' => $data['vacancy_id'], 'employee_id' => $data['user_id']]);
            }
            return false;
        }
        return false;

    }

    public function getMyResponse(array $data)
    {
        $select = ['*', DB::raw("Response.text as response_text"),];
        $result = (new VacancyOfferLogic($data, $select))->setJoin(['Response'])->getOne();
        if (empty($result)) return false;
        return $result;
    }

    public function deleteResponse($data)
    {
        if (empty($data)) return false;
        $responseDel = $this->helpEngine['offer_response']->deleteForeva($data['employee_response_id']);
        $offerDel = $this->helpEngine['vacancy_offer']->deleteForeva($data['id']);

        $vacancyUpdateData = ['executor_id' => null];
        $vacancyUpdateData = setTimestamps($vacancyUpdateData, 'update');

        $vacancyUpdate = (new VacancyLogic())->update($vacancyUpdateData, $data['vacancy_id']);

        if ($responseDel && $offerDel && $vacancyUpdate) {
            return true;
        }
        return false;
    }

    public function acceptWork($data)
    {
        $employeeOffer = (new self($data, [DB::raw("Offer.period as offer_period")]))->setJoin(['Offer'])->getOne();
        $currentDateTime = Carbon::now();

        $vacancy['id'] = $data['vacancy_id'];
        $vacancy['executor_id'] = $data['employee_user_id'];
        $vacancy['status'] = VacancyLogic::STATUS_IN_PROGRESS;

        $vacancy['period_start'] = $currentDateTime->toDateTimeString();
        $vacancy['period_end'] = $currentDateTime->addDays($employeeOffer['offer_period'])->toDateTimeString();

        $vacancy = setTimestamps($vacancy, 'update');

        if ((new VacancyLogic())->store($vacancy)) {
            return ['message' => "you've accept a job offer"];
        }
        return false;
    }

    public function declineWork($data)
    {
        // вернуть оплаченные деньги клиенту
        (new VacancyLogic())->getVacancyLastStatus($data['vacancy_id']);
        $vacancy['id'] = $data['vacancy_id'];
        $vacancy['executor_id'] = null;
//        $vacancy['status'] = VacancyLogic::STATUS_NEW;
//        $vacancy = setTimestamps($vacancy, 'update');
        if ((new VacancyLogic())->store($vacancy)) {
            // кинуть клиенту оповещение об отказе
            return ['message' => "you've decline a job offer"];
        }
        return false;
    }

    protected function getFilter(): array
    {
        $tab = $this->engine->getTable();
        $this->filter = [
            ['field' => $tab . '.id', 'params' => 'user_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                ],
            ['field' => $tab . '.type_id', 'params' => 'type_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
                ],
            ['field' => 'Employee.is_confirmed', 'params' => 'is_confirmed',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
                ],
            ['field' => 'Employee.dt_practice_start', 'params' => 'start_practice_date',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => 'IN', 'concat' => 'AND',
                ],
            ['field' => 'Employee.consultation_price', 'params' => 'consultation_price',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => 'IN', 'concat' => 'AND',
                ],
            ['field' => 'Company.name', 'params' => 'company',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => 'IN', 'concat' => 'AND',
                ],
            [
                'field' => $tab . '.region_id', 'params' => 'region_id',
                'validate' => ['string' => true, "empty" => true], 'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
                ],
            ['field' => 'Offer.vacancy_id', 'params' => 'vacancy_id',
                'validate' => ['string' => true, "empty" => true], 'type' => 'string|array',
                "action" => '=', 'concat' => 'AND',
                ],
            ['field' => $tab . '.city_id', 'params' => 'city_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => '=', 'concat' => 'AND',
                ],
            ['field' => 'InnerJoinService.service_id', 'params' => 'service_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => '=', 'concat' => 'AND',
                'relatedModel' => 'InnerJoinService'
            ],
            ['field' => "CONCAT(user_entity.first_name, ' ', user_entity.last_name)",
                'params' => 'search_spec', 'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => '=', 'concat' => 'AND',
                ],
            [
                  'field' => "TIMESTAMPDIFF(YEAR, Employee.dt_practice_start, DATE(NOW()))",
                'params' => 'experience',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array', "action" => '>=', 'concat' => 'AND',
                ], //            [   'field' => "",'params' => 'rating',
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

    protected function compileGroupParams(): array
    {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' =>
                [
                    'Employee' => [
                        'entity' => new Employee(),
                        'relationship' => ['user_id', 'id'],
                        'field' => [],
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
                        'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id, 'path', CONCAT('/storage', path), 'description',
                                description)) as achievements,
                                user_id FROM user_employee_achievement GROUP BY user_id) as Achievements ON Achievements.user_id = user_entity.id"),
                        'field' => ['achievements'],
                        ],
                    'City' => [
                        'entity' => new City(),
                        'relationship' => ['id', 'city_id'],
                        'field' => ['*'],
                        ],
                    'Region' => [
                        'entity' => new Region(),
                        'relationship' => ['id', 'region_id'],
                        'field' => ['*'],
                        ],
                    'Offer' => [
                        'entity' => new VacancyOffer(),
                        'relationship' => ['employee_user_id', 'id'],
                        'field' => [],
                        ],
                    'Vacancy' => [
                        'entity' => new Vacancy(),
                        'relationship' => ['executor_id', 'id'],
                        'field' => [],
                        ],
                    'Specialization' => [
                        'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('id', id,
                                'service_id', service_id, 'name', (SELECT S.name FROM service as S WHERE S.id = service_id))) as specialization, user_id
                                FROM employee_specializations GROUP BY user_id) as Specialization ON Specialization.user_id = user_entity.id"),
                        'field' => ['specialization'],
                        ],
                    'WorkingSchedule' => [
                        'entity' => DB::raw("(SELECT JSON_ARRAYAGG(
                                JSON_OBJECT('time_from', TIME_FORMAT(time_from, '%H:%i'),
                                'time_to', TIME_FORMAT(time_to, '%H:%i'),
                                'day_of_week', (SELECT DW.abbreviation FROM days_of_week AS DW WHERE DW.id = day_of_week), 'day_number', day_of_week)) as schedule, user_id
                                FROM employee_working_schedules GROUP BY user_id) as WorkingSchedule ON WorkingSchedule.user_id = user_entity.id"),
                        'field' => ['schedule'],
                        ],
                    ]
        ];
        return $this->group_params;
    }

//(SELECT DW.name FROM days_of_week AS DW WHERE DW.id = id)
}
