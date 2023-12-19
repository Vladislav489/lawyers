<?php
/**
 * Created by PhpStorm.
 * User: WORK
 * Date: 16.01.2023
 * Time: 11:01
 */

namespace App\Models\CoreEngine\Model;

class SystemSeo extends ModyfiModel {
    protected $table = 'system_seo';
    protected $fillable = [
        'site_id',
        'template_id',
        'route_id',
        'title',
        'lang_id',
        'keywords',
        'description',
        'active',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'site_id' =>"Site Id",
                'template_id'=>"Template id",
                'route_id'=>"Route id",
                'title' => "Title",
                'lang_id'=>"Lang",
                'keywords' => 'Keywords',
                'description' =>  'Description',
                'lang_id'=>"Lang",
                'is_deleted'=>"Deleted",
                'active'=>'Show',
                'created_at'=>"Created date",
                'updated_at'=>"Update date",
            ],
            'rus' =>[
                'site_id' =>"Сайт ",
                'template_id' => "Темплейт",
                'route_id' => "Genm",
                'title' => "Заголовок",
                'keywords' => "Ключивые слова",
                'description' =>" Дискрипшен",
                'lang_id'=>"Язык",
                'active'=>'Активный',
                'is_deleted'=>"Удален",
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