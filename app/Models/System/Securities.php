<?php
namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Securities extends Model {

    const TYPE_DIGITAL  = 2;
    const TYPE_NASDAQ   = 1;
    const TYPE_PHYSICAL = 3;



    protected $table = 'list_securities_code';

    protected $fillable = [
        'short_name',
        'name',
        'type'
    ];

    public static function getTypeSTR($lang = null,$type = null){
        $lable =  [
            'eng' => [
                2=>"Cryptocurrencies",
                1=>"Stocks",
                3=>"Currencies"
            ],
            'rus' =>[
                2=>"Криптовалюта",
                1=>"Акции",
                3=>"Валюта"
            ],
        ];
        if(is_null($lang)){
            $lang =  Session::get('lang');
            if(is_null($type))
                return $lable[$lang[0]];
            else
                return $lable[$lang[0]][$type];
        }else{
            if(is_null($type)) {
                return $lable[$lang];
            }else
                return $lable[$lang][$type];
        }
    }
}
