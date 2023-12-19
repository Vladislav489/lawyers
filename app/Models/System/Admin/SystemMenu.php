<?php
namespace App\Models\System\Admin;

use App\Models\CoreEngine\Model\ModyfiModel;

class SystemMenu extends ModyfiModel  {
    protected $table = 'system_menu';
    protected $fillable = [
        'parent_id',
        'site_id',
        'lable',
        'url','sort','lang_id',
        'icon',
        'active','is_deleted', 'created_at',
        'updated_at'
    ];


    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'id'=>'Id',
                'parent_id'=>'Main group',
                'site_id'=>'Site',
                'lable'=>'Title',
                'url'=>'Url',
                'sort'=>'Sort',
                'lang_id'=>'Lang',
                'active'=>'Active',
                'icon'=>"Icon",
                'is_deleted'=>'Is Deleted',
                'created_at'=>'Date Created',
                'updated_at'=>'Date Updated'
            ],
            'rus' =>[
                'id'=>'Id',
                'parent_id'=>'Главная группа',
                'site_id'=>'Сайт',
                'lable'=>'Заголовок',
                'url'=>'Адресс',
                'sort'=>'Сортировка',
                'lang_id'=>'Язык',
                'active'=>'Активный',
                'is_deleted'=>'Удален',
                'icon'=>"Иконка",
                'created_at'=>'Дата Создания',
                'updated_at'=>'Дата Обновления'
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
