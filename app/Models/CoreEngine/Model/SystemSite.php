<?php
/**
 * Created by PhpStorm.
 * User: WORK
 * Date: 16.01.2023
 * Time: 11:01
 */

namespace App\Models\CoreEngine\Model;

class SystemSite extends ModyfiModel {
    protected $table = 'system_site';
    protected $fillable = [
        'domain_name',
        'user_main_id',
        'active',
        'style',
        'lang_id',
        'is_delete',
        'created_at',
        'updated_at',
    ];
    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'domain_name'=>"Domain name",
                'user_main_id'=>"Owner Site",
                'style'=>"Style site",
                'lang_id'=>"Lang",
                'is_delete'=>"Deleted",
                'active'=>'Show',
                'created_at'=>"Created date",
                'updated_at'=>"Update date",
            ],
            'rus' =>[
                'domain_name'=>"Домен",
                'user_main_id'=>"Владелец сайта",
                'style'=>"Стиль",
                'lang_id'=>"Язык",
                'active'=>'Активный',
                'is_delete'=>"Удален",
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