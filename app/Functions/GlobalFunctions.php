<?php
$includeFileSystem = [];

function route__($name,$params = []) {
    try {
        return route($name, $params, false);
    }catch (\Throwable $e){
        Artisan::call("route:clear");
        \Illuminate\Support\Facades\Cache::tags([\App\Models\System\General\Site::getSite()['domain_name']])
            ->delete(\App\Models\System\RouteBilder::getCacheName());
        try {
            return route($name, $params, false);
        }catch (\Throwable $e){
          //  abort(404);
        }
    }
}


function getSetting($key,$site = true,$user = false){

    if($site && !$user) {
        return \App\Models\System\General\SettingStorage::getInstance()->getSetting($key);
    }
    if($site && $user)
        return \App\Models\System\General\SettingStorage::getInstance()->getSettingUser($key);
}

function componetBuild($data){
    $componemt = new \App\Models\System\Component\ComponentBuilder();
    return $componemt->buildComponent($data);
}
