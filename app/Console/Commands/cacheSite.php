<?php

namespace App\Console\Commands;
use App\Models\CoreEngine\LogicModels\ComboSite\SiteMapLogic;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class cacheSite extends Command {

    protected $signature = 'cache_site:build_cache';
    protected $description = 'run chart cron yahoo API';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $table = SiteMapLogic::getTable();
        $dataForStep =  (new SiteMapLogic())->pagination(0,3000);
        $params = ['page' => (string)1,'pageSize' => (string)3000,"status_cache"=>(string)0];
        for ($page = 1; $page <= $dataForStep['countPage']; $page++){
            $targetList = (new SiteMapLogic($params,['id','url','cache']))->getList();
            $update = [];
           // var_dump($page,$targetList['pagination'],count($targetList['result']));
           // echo "<br>";
            for($index = 0;  $index < count($targetList['result']); $index++){
                if($targetList['result'][$index]['cache'] == 0){
                    $client = new Client();
                   // $client = new Http();
                    $options =
                    [
                        'http_errors' => true,
                        'force_ip_resolve' => 'v4',
                        'connect_timeout' => 10,
                        'read_timeout' => 10,
                        'timeout' => 10,
                    ];

                    //UPDATE `system_sitemap` SET `cache` = '0' WHERE `url` LIKE 'https://dev.fin-pr.ru/forecast/cryptocurrencies/%';
                    try {
                        $urlAndSpecParams = $targetList['result'][$index]['url']."?cache_build=".Config::get('app.borderToken');
                        //$result = Http::get($targetList['result'][$index]['url'],['cache_build'=>Config::get('app.borderToken')]);
                         $result = $client->request("GET",$urlAndSpecParams, $options);
                         var_dump($urlAndSpecParams);
                    }catch (\Throwable $e){

                    }

                    $update[] = $targetList['result'][$index]['id'];
                }
            }
            $ids = implode("','",$update);
            DB::statement("UPDATE {$table} SET cache = '1' WHERE id IN('{$ids}')");
        }




       return 0;
    }


}