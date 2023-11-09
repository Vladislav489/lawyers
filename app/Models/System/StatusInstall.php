<?php
namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class StatusInstall extends Model {

    const TYPE_YAHOO  = 1;
    const TYPE_CSV_CODE = 2;
    const TYPE_IMG  = 3;
    const TYPE_MAIN  = 4;

    protected $table = 'system_status_import_start';


    public static $listAction = [
       ['title' => 'parserDigital','type' => self::TYPE_CSV_CODE],
       ['title' => 'parserNasdaq','type' => self::TYPE_CSV_CODE],
       ['title' => 'parserPhysical','type' => self::TYPE_CSV_CODE],

       ['title' =>'initInformationData','type' => self::TYPE_MAIN],

       ['title' => 'loadDigital','type' => self::TYPE_YAHOO ],
       ['title' => 'loadPhysical','type' => self::TYPE_YAHOO ],
       ['title' => 'loadNasdaq','type' => self::TYPE_YAHOO ],

       ['title' => 'loadDigitalImage' ,'type' => self::TYPE_IMG],
       ['title' => 'loadPhysicalImage','type' => self::TYPE_IMG],
       ['title' => 'loadNasdaqImage','type' => self::TYPE_IMG],

    ];

    protected $fillable = [
        'title',
        'type',
        'status',
        'error'
    ];


   public static function chengeStatus(){
       
   }

}
