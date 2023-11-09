<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 05.07.2023
 * Time: 21:23
 */

namespace App\Models\CoreEngine\Model;
use Illuminate\Database\Eloquent\Model;

class SystemSettingStoreg extends Model {
    protected $table = 'system_setting_storeg';
    protected $fillable = [
        'key',
        'site_id',
        'user_id',
        'lang_id',
        'value',
        'created_at',
        'updated_at'
    ];
    public function getLable($lang = null,$type = null){
        $lable =  [
            'eng' => [
                'site_id' =>"Site id",
                'user_id'=>"User id",
                'lang_id'=>"Lang",
                'value' => "Setting",
                'created_at'=>"Created date",
                'updated_at'=>"Update date",
            ],
            'rus' =>[
                'site_id' =>"Сайт ",
                'user_id'=>"Пользователь",
                'lang_id'=>"Язык",
                'value' => "Настройки",
                'created_at'=>"Дата Создания",
                'updated_at'=>"Дата Обновления",
            ],
        ];
        if(is_null($lang)){
            $lang = Session::get('lang');
            return (is_null($type))?$lable[$lang[0]]:$lable[$lang[0]][$type];
        }else{
            return (is_null($type))?$lable[$lang]:$lable[$lang][$type];
        }
    }



}