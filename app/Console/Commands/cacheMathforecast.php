<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 18.04.2023
 * Time: 15:45
 */

namespace App\Console\Commands;

use App\Models\CoreEngine\LogicModels\Forecast\InformationalDataLogic;
use App\Models\CoreEngine\LogicModels\Forecast\ForecastLogic;
use App\Models\System\Securities;
use Illuminate\Console\Command;

class cacheMathforecast extends Command{
    protected $signature = 'site:math_cache';

    protected $description = 'create cache for math Forecast';
    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        set_time_limit(0);

        $list = new InformationalDataLogic([
            "price" => 0,
            "sort_more" => 0
        ]);
        $result = $list->getListClearData();
        var_dump($result['pagination']);
        for( $page = 1; $page <= $result['pagination']['countPage']; $page++){
            $data =  new InformationalDataLogic([
                "price" => 0,
                "sort_more" => 0,
                "page" => $page
            ],['short_name','type_inf_id','id']);
            $data__ = $data->offPagination()->getListClearData();
            foreach ($data__['result'] as $key => $item ){
                if ($item['type_inf_id'] == Securities::TYPE_PHYSICAL) {
                    $currence = new InformationalDataLogic([
                        "price" => 0,
                        "type" => (string)Securities::TYPE_PHYSICAL,
                    ],['short_name',"full_name","type_inf_id",""]);
                    $curr =  $currence->setLimit(500)->offPagination()->getListClearData();
                    if(isset($curr['result'])) {
                        foreach ($curr['result'] as $item__) {
                            ForecastLogic::forAllForecast(['code' => $item['short_name'] . "-" . $item__['short_name'], 'type' => (string)$item['type_inf_id']], true);
                            ForecastLogic::forYear(['code' => $item['short_name'] . "-" . $item__['short_name'], 'type' => (string)$item['type_inf_id']], true);

                        }
                    }
                } else {
                        ForecastLogic::forAllForecast(['code' => $item['short_name'], 'type' => (string)$item['type_inf_id']], true);
                        ForecastLogic::forYear(['code' => $item['short_name'], 'type' => (string)$item['type_inf_id']], true);
                }
            }
        }
        return 0;
    }
}