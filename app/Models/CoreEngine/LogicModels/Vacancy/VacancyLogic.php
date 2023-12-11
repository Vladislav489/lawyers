<?php

namespace App\Models\CoreEngine\LogicModels\Vacancy;

use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\ProjectModels\Vacancy\Vacancy;
use App\Models\CoreEngine\Model\InformationCategoryName;
use Illuminate\Support\Facades\Auth;

class VacancyLogic extends CoreEngine
{
    public function __construct($params = [], $select = ['*'], $callback = null)
    {
        $this->engine = new Vacancy();
        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();

        parent::__construct($params, $select);
    }



    public function store(array $data): array
    {
        /*$default = ['defendant' =>  json_encode([]), 'status' => 1, 'lawsuit_number' => 1,'address_judgment' => 1,
            'period_start' => '2023-01-01' , 'period_end' => '2023-01-01' ,'priority_id' => 1, 'chat_id' =>1,
            'service_id' => 1, 'executor_id' => null ,'country_id' => 1,'state_id' =>1,'city_id' => 1];
        $data['user_id'] = Auth::id();
        $data = array_merge($data,$default);
        $vacancyId = $this->save($data);
        if($vacancyId){
            $data['id'] = $vacancyId;
            return $data;
        }
        return false;
        */


        $vacancy = new Vacancy();

        $vacancy->description = $data['description'];
        $vacancy->payment = $data['payment'];
        $vacancy->defendant = json_encode([]);
        $vacancy->status = '1';
        $vacancy->lawsuit_number = '1';
        $vacancy->address_judgment = '1';
        $vacancy->period_start = '2023-01-01';
        $vacancy->period_end = '2023-01-01';

        $vacancy->priority_id = 1;
        $vacancy->chat_id = 1;
        $vacancy->user_id = Auth::id();

        $vacancy->service_id = 1;
        $vacancy->executor_id = null;
        $vacancy->country_id = 1;
        $vacancy->state_id = 1;
        $vacancy->city_id = 1;

        $vacancy->save();

        return $vacancy;
    }

    public function update2(int $vacancy_id, array $data)
    {
        //если нужна частичное обновление
        /*
        if(isset($data['id']) && !empty($data['id']) && is_numeric($data['id'])) {
            $id = $data['id']
            unset($data['id']);
            $this->update($data,$id); или  $this->update($data,$vacancy_id);
        }
        я не делаю лишний запрос и обновляю только нужную часть
       */

        $vacancy = Vacancy::find($vacancy_id);

        $vacancy->description = $data['description'];
        $vacancy->payment = $data['payment'];
        $vacancy->save();

        return $vacancy;
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

    protected function defaultSelect(): array
    {
        $tab = $this->engine->tableName();
        $this->default = [];

        return $this->default;
    }

    protected function getFilter(): array
    {
        // Для выборок описуешь тут правила
        /*
         * 'field'=>$tab.'.name', поле которое ищем также можно пихнуть подзапрос или Raw
         * 'params' => 'name', название параметра которое прийдет из риквеста
           'validate' =>['string'=>true,"empty"=>true], говорим что страка и он может быть не оюязательгым
           'type' => 'string|array', смешаный тип или стринг или массив стрингов
           "action" => 'IN',  оператор который выполнить для where  дополнительный обработки CoreParams function preprocessingWhereAction
           'concat' => 'AND',
           'relatedModel'=>"CategoryName" обязательный джой  таблицы для этого фильтрв
          *
         * */
        $tab = $this->engine->getTable();
        $this->filter = [];
        $this->filter = array_merge($this->filter, parent::getFilter());

        return $this->filter;
    }
}
