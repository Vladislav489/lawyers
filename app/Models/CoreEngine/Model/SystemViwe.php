<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11.01.2023
 * Time: 15:43
 */

namespace App\Models\CoreEngine\Model;


class SystemViwe extends ModyfiModel{

    protected $table = 'system_view';
    protected $fillable = [
        'site_id',
        'user_id',
        'lang_id',
        'type_page_id',
        'name_view',
        'url_route',
        'body_view',
        'body_script_view',
        'body_link_view',
        'body_title_view',
        'body_meta_view',
        'body_bottom_script_view',
        'route_id',
        'active',
        'is_delete',
        'created_at',
        'updated_at',

    ];
    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'site_id'=>"Sate Id",
                'user_id'=>"User Id",
                'lang_id'=>"Lang Id",
                'url_route'=>"Url",
                'type_page_id'=>"Type page",
                'name_view'=>"View",
                'body_view'=>"Code Page",
                'body_script_view'=>"Script Page",
                'body_link_view'=>"Style Page",

                'body_title_view'=>"Title Page",
                'body_meta_view'=>"Meta Page",
                'body_bottom_script_view' => "Script bottom page",
                'route_id'=>"Route Id",
                'active'=>"Active",
                'is_delete'=>"Id Deeleted",
                'created_at'=>"Date Create",
                'updated_at'=>"Date Update",
            ],
            'rus' =>[
                'site_id'=>"Сайт айди",
                'user_id'=>"Пользователь Id",
                'lang_id'=>"Язык",
                'url_route'=>"Адресс",
                'type_page_id'=>"Тип страницы",
                'name_view'=>"Темплейт",
                'body_view'=>"Код страницы",
                'body_script_view'=>"Скрипт страницы",
                'body_link_view'=>"Стили страницы",

                'body_title_view'=>"Заголовок страницы",
                'body_meta_view'=>"Мета данные страницы",
                'body_bottom_script_view' => "Скрипт в конце страны",
                'route_id'=>"Путь",
                'active'=>"Активный",
                'is_delete'=>"Удален",
                'created_at'=>"Дата создания",
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