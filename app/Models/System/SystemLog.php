<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.08.2022
 * Time: 16:13
 */

namespace App\Models\System;

use App\Models\CoreEngine\Model\ModyfiModel;
use Illuminate\Support\Facades\Session;

class SystemLog extends  ModyfiModel {

    const CODE_MESSAGE  = 1;
    const CODE_ERROR    = 2;
    const CODE_SYSTEM   = 4;
    const CODE_TASK     = 5;
    const CODE_IMPORT   = 6;

    protected $table = 'system_log';
    protected $fillable = [
        'title',
        'log',
        'short_text',
        'code',
        'created_at'
    ];



    public static function getListConst($lang = null,$type = null){
        $lable = [
            'eng'=>[],
            'rus'=>[
                self::CODE_MESSAGE=>"Сообщение",
                self::CODE_ERROR=>"Ошибки",
                self::CODE_SYSTEM => "Системные",
                self::CODE_TASK => "Задачи",
                self::CODE_IMPORT =>"Импорт"
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

    public static function  addLog($title,$log,$short_text = "",$code = self::CODE_MESSAGE){
        try {
            self::query()->insert([
                    'title' => $title,
                    'log' => json_encode($log),
                    'short_text' => $short_text,
                    'code' => $code
                ]
            );
        }catch (\Exception $e){
            dd($e->getMessage(),$e->getTrace());
        }
    }
}
