<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.11.2022
 * Time: 16:34
 */
namespace App\Models\System;


use Illuminate\Support\Facades\DB;

class HelperFunction{
    const  FROM_TO_LESS     = 1;
    const  FROM_TO_MORE     = 2;
    const  FROM_TO_CENTERT  = 3;


    const  ROUND_CURRENCY_MORE_10000 = 0;
    const  ROUND_CURRENCY_MORE_1000_LESS_10000 = 1;
    const  ROUND_CURRENCY_MORE_2_LESS_1000 = 2;
    const  ROUND_CURRENCY_MORE_1_LESS_2 = 2;
    const  ROUND_CURRENCY_LESS_1 = 3;

    public static function ArrayForSelectFomCodeEngine($array_result,$id_colName,$tite_col){
        $return = [];
        foreach ($array_result as $key => $value){
            $return[$value[$id_colName]] = $value[$tite_col];
        }
        return $return;
    }
    public static function round_forecast($float){

        if($float > 10000){
            return  round($float,self::ROUND_CURRENCY_MORE_10000);
        } elseif ($float > 1000 && $float < 10000){
            return  round($float,self::ROUND_CURRENCY_MORE_1000_LESS_10000);
        } elseif ($float > 2 && $float < 1000){
            return  round($float,self::ROUND_CURRENCY_MORE_2_LESS_1000);
        } elseif ($float > 1 &&  $float < 2){
           return self::round_special($float,self::ROUND_CURRENCY_MORE_1_LESS_2);
        } elseif ($float < 1) {
           return ($float == 0)? "0":self::round_special($float,self::ROUND_CURRENCY_LESS_1);
        }
    }

    public static function round_special($float,$countIntFromEnd){
        $floatString = (float)$float;
        $floatString = sprintf("%01.20f", $floatString);
        $floatExplode = explode('.', $floatString);
        $counter = 0;
        $flagLineziro = false;
        $NewLeng = 0;
        for($index = 0;$index < strlen($floatExplode[1]);$index++){
            if ($floatExplode[1][$index] != "0") {
                $flagLineziro = true;
            } else {
                if(!$flagLineziro)
                    $NewLeng++;
            }
            if ($flagLineziro &&  $counter != $countIntFromEnd ) {
                $counter++;
                $NewLeng++;
            }
        }
        $left = substr($floatExplode[1],0,$NewLeng);
        $right = substr($floatExplode[1],$NewLeng);
        $sum = 0;
        for ($i = strlen($right)-1; $i >= 0; $i--) {
            $number = (int) $right[$i];
            $number += $sum;
            if (is_numeric($number)) {
                $sum = ($number >= 5)?1:0;
            }
        }
        $left = sprintf('%0'.$NewLeng.'d', $left+$sum);
        return $floatExplode[0].".".$left;
    }


    public static function SortLableByArray($arr_lable,$sortArra){
        $return = [];
        foreach ($sortArra as $sort){
            foreach ($arr_lable as $key => $target){
                if(isset($target['key']) && $target['key'] == $sort) {
                    $return[] = $target;
                    unset($arr_lable[$key]);
                    break;
                }
            }
        }

        if(count($return) == 0){
            return $arr_lable;
        }
        return $return;
    }
    public static function array_to_string($array) {
            $export = var_export($array, TRUE);
           /* $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
            $array = preg_split("/\r\n|\n|\r/", $export);
            $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
            $export = join(PHP_EOL, array_filter(["["] + $array));*/
        return $export;
    }

    public static function BuildSelectColumsForEngin($arr){
        $return = [];
        foreach ($arr as $item) {
            if(key_exists('db',$item)) {
                $return[] = $item['db'];
            }else{
                $return[] = DB::raw($item['table'].".".$item['column']);
            }
        }
        return $return;
    }
    public static function missingColumnLabel($lable,$cols){
         $transform = [];
         $addColumnMissing = [];
         foreach ($lable as $key => $item){
             $transform[$item['key']] = $item['name'];
         }

         foreach ($cols as $key => $item){
             if(!isset($transform[$key])){
                 $lable[] = ['key'=>$key,'name'=>$key];
             }
         }
         return $lable;
    }
    public static function ignoryColumnLable($lable,$ignory){
           foreach ($lable as $key => $item){
               if (in_array($item['key'],$ignory)) {
                   unset($lable[$key]);
               }
           }
           return $lable;
    }
    public static function joinColumLabel($arr){
        $return = [];
        foreach ($arr as $ColumItem) {
            foreach ($ColumItem as $colum) {
                if((isset($colum['key']) && isset($colum['name'])) && $colum['key'] != $colum['name']){
                    $return[] = $colum;
                }
            }
        }
        return $return;
    }
    public static function getLableForQuery($rezQuery,$labelArr,$ignory = []){
        $keyQuyery = array_keys($rezQuery);
        $returnColumn = [];
        try {
            foreach ($keyQuyery as $key => $value) {
                if (!in_array($value, $ignory)) {
                    if (array_key_exists($value, $labelArr)) {
                        $returnColumn[] = ['key' => $value, 'name' => $labelArr[$value]];
                    } else {
                        $returnColumn[] = ['key' => $value, 'name' => $value];
                    }
                }
            }
        }catch (\Throwable $e){
            dd($keyQuyery,$labelArr);
        }
        return $returnColumn;
    }

    public static function getMaxDate($listDate){
    $maxData = 0;
    $returnValue = "";
        foreach ($listDate as $item) {
            if ($maxData < strtotime($item)) {
                $maxData = strtotime($item);
                $returnValue = $item;
            }
        }
        return (!empty($returnValue))? $returnValue:false;
    }

    public static function getMinDate($listDate){
        $minDate = strtotime(self::getMaxDate($listDate));
        foreach ($listDate as $item) {
            if ($minDate > strtotime($item)) {
                $minDate = strtotime($item);
                $returnValue = $item;
            }
        }
        return (!empty($returnValue))? $returnValue:false;
    }
    public static function getDateForstAndLastDayInMonthYears($date_from,$date_to){
        $yearFrom  = date("Y",strtotime($date_from));
        $yearTo    = date("Y",strtotime($date_to));
        $forecast = [];
        $currentYear = date("Y",strtotime($date_to));
        for($year_ = $yearFrom; $year_ <= $yearTo;$year_++) {
            for ($month = 1;$month <= 12;$month++) {
                $forecast[] = $year_."-".sprintf("%1$02d",$month)."-01";
                $forecast[] = $year_."-".sprintf("%1$02d",$month)."-".HelperFunction::getCountDayInMonth(date($year_."-".sprintf("%1$02d",$month)."-01"));
            }
        }
        return $forecast;
    }
    public static function rand_float($min,$max,$mul = 100000){
        if ($min>$max) return false;
        return @mt_rand($min*$mul,$max*$mul)/$mul;
    }
    public static function getCountDayBeetwin($date_more,$date_less){
        $earlier = new \DateTime($date_less);
        $later = new \DateTime($date_more);
        return $later->diff($earlier)->format("%a");
    }
    public static function getCountDayInMYear($date){
        $dataObj = new \DateTime($date);
        $year = $dataObj->format("Y");
        return date('z',strtotime($year."-12-31"))+1;
    }
    public static function getListDateByDayCountLess($day,$date,$flag){
        $list = [];
        for($index = 1 ;$index <= $day; $index++){
            $plusMinus = "";
            $modify = new \DateTime($date);
            switch ($flag){
                case HelperFunction::FROM_TO_LESS:
                    $plusMinus = "-";
                    break;
                case  HelperFunction::FROM_TO_MORE:
                    $plusMinus = "+";
                    break;
                default:
                    $plusMinus = null;
                    break;
            }
            if($plusMinus == '-') {
                $list[] = $modify->modify(" -" . $index . " days")->format("Y-m-d");
            }
            if($plusMinus == '+') {
                $list[] = $modify->modify( " +".$index." days")->format("Y-m-d");
            }
        }
        return $list;
    }

    public static function array_insert(&$array, $position, $insert){
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
        }
    }
    public static function getCountDayBeetwinDate($from,$to){
        $fromStamp = strtotime($from);
        $toStamp = strtotime($to);
        $datediff = $toStamp - $fromStamp;

        echo floor($datediff / (60 * 60 * 24)); // вычислим количество дней из разности дат
    }
    public static function getCountDayInMonth($date){
            $dataObj = new \DateTime($date);
            return cal_days_in_month(CAL_GREGORIAN, str_replace('0', '', $dataObj->format('m')), $dataObj->format('Y'));
    }
    public static function getFromToByParams($date,$type,$diff = null , $flag = null ){
        $dataObj = new \DateTime($date);
        $dataObjFrom = new \DateTime($date);
        $dataObjTo = new \DateTime($date);
        $result = ['from'=>null,'to'=>null,'data_modidy'=>null,'from_beetwin'=>null,'to_beetwin'=>null];
        switch ($flag){
            case self::FROM_TO_LESS:
                $plusMinus = "-";
                break;
            case self::FROM_TO_MORE:
                $plusMinus = "+";
                break;
            case self::FROM_TO_CENTERT:
                $plusMinus = false;
                break;
            default:
                $plusMinus = null;
                break;
        }

        switch ($type){
            case "month":
            case "week":
            case "day":
                if(is_null($diff)){
                    $result['from'] = $dataObjFrom->modify('first day of this '.$type);
                    $result['to'] = $dataObjTo->modify('last day of this '.$type);
                }else{
                    if($plusMinus == '-') {
                        $result['from'] = $dataObjFrom->modify(" -" . $diff . " " . $type);
                        $result['to'] =  new \DateTime($date);
                    }
                    if($plusMinus == '+') {
                        $result['from'] = new \DateTime($date);
                        $result['to'] = $dataObjTo->modify( " +".$diff." ".$type);
                    }
                    if(!is_null($plusMinus) &&  $plusMinus !== false) {
                        $result['data_modidy'] = $dataObj->modify( $plusMinus.$diff." ".$type);
                    }else{
                        $half = (int)$diff/2;

                        $result['from_beetwin'] = $dataObjFrom->modify( " -".$half." ".$type);
                        $result['to_beetwin'] = $dataObjTo->modify( " +".$half." ".$type);
                    }
                }
                break;
            case "year":
                    if($plusMinus == '-') {
                        $result['from'] = $dataObjFrom->modify(" -" . $diff . " " . $type);
                        $result['to'] =  new \DateTime($date);
                    }
                    if($plusMinus == '+') {
                        $result['from'] = new \DateTime($date);
                        $result['to'] = $dataObjTo->modify( " +".$diff." ".$type);
                    }

                    if(!is_null($plusMinus) &&  $plusMinus !== false) {
                        $result['data_modidy'] = $dataObj->modify( $plusMinus.$diff." ".$type);
                    }else{
                        $half = (int)$diff/2;
                        $result['from_beetwin'] = $dataObjFrom->modify( " -".$half." ".$type);
                        $result['to_beetwin'] = $dataObjTo->modify( " +".$half." ".$type);
                    }
                break;
            case "minutes":
            case "hour":
                    if($plusMinus == '-') {
                        $result['from'] = $dataObjFrom->modify(" -" . $diff . " " . $type);
                        $result['to'] =  new \DateTime($date);
                    }
                    if($plusMinus == '+') {
                        $result['from'] = new \DateTime($date);
                        $result['to'] = $dataObjTo->modify( " +".$diff." ".$type);
                    }
                    if(!is_null($plusMinus) &&  $plusMinus !== false) {
                        $result['data_modidy'] = $dataObj->modify( $plusMinus.$diff." ".$type);
                    }else{
                        $half = (int)$diff/2;
                        $result['from_beetwin'] = $dataObjFrom->modify( " -".$half." ".$type);
                        $result['to_beetwin'] = $dataObjTo->modify( " +".$half." ".$type);
                    }
                break;
            default:
                $result['data_modidy'] = $dataObj->modify($type);
                break;
        }
        return  $result;
    }

    public static function size_format( $bytes, $decimals = 0 ) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // раскомментируйте одну из следующих строк
        $bytes /= pow(1024, $pow);
        //$bytes /= (1 << (10 * $pow));

        return round($bytes, $decimals) . ' ' . $units[$pow];
    }
}
