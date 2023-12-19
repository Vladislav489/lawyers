<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 28.02.2023
 * Time: 19:40
 */

namespace App\Models\CoreEngine\Model;


class InformationTypeName extends ModyfiModel{
    protected $table = 'info_type_informational_name';
    protected $fillable = [
        'name',
        'sort',
        'parent_id',
        'site_id',
        'lang_id',
        'active',
        'is_deleted',
        'created_at',
        'updated_at',
    ];


    public function getLable($lang = null,$type =null){
        $lable =  [
            'eng' => [
                'name' =>"Name",
                'alias_url'=>"Alias Url",
                'parent_id'=>"Parent",
                'site_id'=>"Site",
                'lang_id'=>"Lang",
                'sort' => "Sort",
                'active'=>"Active",
                'is_deleted'=>"Deleted",
                'created_at' =>"Date Create",
                'updated_at'=>"Date Update",
            ],
            'rus' =>[
                'name' =>"Имя типа",
                'alias_url'=>"Псевдоним в url",
                'parent_id'=>"Родитель",
                'site_id'=>"Сайт",
                'lang_id'=>"Язык",
                'sort' => "Сортировка",
                'active'=>"Активен",
                'is_deleted'=>"Удален",
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