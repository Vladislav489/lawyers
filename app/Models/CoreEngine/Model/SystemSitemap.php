<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 05.06.2023
 * Time: 13:16
 */

namespace App\Models\CoreEngine\Model;


class SystemSitemap extends  ModyfiModel {
    protected $table = 'system_sitemap';
    protected $fillable = [
            'url',
            'route_id',
            'site_id',
            "lang_id",
            'is_deleted',
            'cache',
            'created_at',
            'updated_at'
    ];
    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'url' => "Url",
                'route_id' => "Route Id",
                'site_id' => "Site Id",
                'lang_id' => "Lang",
                'is_deleted'=>"Deleted",
                'cache' => "Cache",
                'created_at'=>"Created date",
                'updated_at'=>"Update date",
            ],
            'rus' =>[
                'url' => "Урл",
                'route_id' => "Роут",
                'site_id' => "Сайт",
                'lang_id' => "Язык",
                'is_deleted'=>"Удален",
                'cache' => "Кеш",
                'created_at'=>"Дата создания",
                'updated_at'=>"Дата Обновления",
            ],
        ];
        if(is_null($lang)){
            $lang =  Session::get('lang');
            if(is_null($type))
                return $lable[$lang[0]];
            else
                return $lable[$lang[0]][$type];
        }else{
            if(is_null($type))
                return $lable[$lang];
            else
                return $lable[$lang][$type];
        }
    }
}
