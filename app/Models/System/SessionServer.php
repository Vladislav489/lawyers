<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.03.2023
 * Time: 19:24
 */

namespace App\Models\System;
use Illuminate\Support\Facades\File;

class SessionServer{
    public static function getAllSessionDomains($domain){
        $path = config('session.files');
        $session  = [];
        if (File::exists($path)) {
            $files = File::allFiles($path);
            foreach ($files as $key => $value){
                if(file_exists($value->getPathname())) {
                   $text =  file_get_contents($value->getPathname());
                   if(strpos($text,$domain) !== false){
                       $session[$value->getPathname()] = $text;
                   }
                   unset($text);
                }
            }
        } else {
            error_log('check your session path exists');
        }
        return  $session;
    }
    public static function deleteSessionDomains($data){
        $session = self::getAllSessionDomains($data['domain_name']);
        foreach ($session as $key => $value){
            if(file_exists($key))
                    unlink($key);
        }
    }
    public static function updateSessionVariableDomains($keyValue,$data){
           $session = self::getAllSessionDomains($data['domain_name']);
           foreach ($session as $key => $value){
                $data__ = unserialize($session[$key]);
                $data__[$keyValue] = $data;
                $session[$key] = serialize($data__);
                if(file_exists($key)){
                      $result = file_put_contents($key,$session[$key]);
                }
           }
    }
}