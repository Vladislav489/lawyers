<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27.02.2023
 * Time: 16:46
 */

namespace App\Models\CoreEngine\Model;
use App\Models\CoreEngine\Model\ModyfiModel;

class InformationType extends ModyfiModel{
    protected $table = 'info_type_informational';
    protected $fillable = [
        'alias_url',
        'parent_id',
        'site_id',
        'active',
        'is_delete',
        'created_at',
        'updated_at',
    ];


    public function getLable($lang = null,$type =null){
        $lable =  [
            'eng' => [
                'name' =>"Name",
                'alias_url'=>"Alias Url",
                'parent_id'=>"Parent",
                'sort'=>"Sort",
                'site_id'=>"Site",
                'lang_id'=>"Lang",
                'active'=>"Active",
                'is_delete'=>"Deleted",
                'created_at' =>"Date Create",
                'updated_at'=>"Date Update",
            ],
            'rus' =>[
                'name' =>"Имя типа",
                'alias_url'=>"Псевдоним в url",
                'parent_id'=>"Родитель",
                'site_id'=>"Сайт",
                'sort'=>"Сортировка",
                'lang_id'=>"Язык",
                'active'=>"Активен",
                'is_delete'=>"Удален",
                'created_at' =>"Дата создания",
                'updated_at'=>"Дата обновления",
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