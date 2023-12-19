<?php
namespace App\Models\CoreEngine\Model;

use App\Models\CoreEngine\Model\ModyfiModel;

class SystemRouts extends ModyfiModel {
    protected $table = 'system_route';
    protected $fillable = [
        'site_id',
        'url',
        'template_url',
        'name_title',
        'active',
        'alias_url',
        'type_page',
        'page_id',
        'lang_id',
        'open',
        'check_module',
        'physically',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'site_id' =>"Site id",
                'url'=>"Url",
                'template_url'=>"Template Url",
                'name_title'=>"Name page",
                'alias_url'=>"Alias Url",
                'page_id'=>"Page Id",
                'check_module'=>"Module Check",
                'lang_id'=>"Lang",
                'type_page'=>"Type page",
                'is_deleted'=>"Deleted",
                'active'=>'Show',
                'open'=>"Open/Close",
                'physically'=>"Physically",
                'created_at'=>"Created date",
                'updated_at'=>"Update date",
            ],
            'rus' =>[
                'site_id'=>"Сайт айди",
                'url'=>"Адресс",
                'template_url'=>"Темплейт Урл",
                'name_title'=>"Название стр.",
                'alias_url'=>"Псевдоним",
                'page_id'=>"Страница",
                'check_module'=>"Модуль проверки",
                'lang_id'=>"Язык",
                'active'=>'Активный',
                'open'=>"Отк/Закр",
                'type_page'=>"Тип стр.",
                'physically'=>"Физический",
                'is_deleted'=>"Удален",
                'active'=>"Статус",
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