<?php
$includeFileSystem = [];

function route__($name,$params = []) {
    try {
        return route($name, $params, false);
    } catch (\Throwable $e) {
        \App\Models\System\SystemLog::addLog(
            "Route не найдет была сделане перезагрузка кеша",
            [
                'massage' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile()." Line ",$e->getLine(),
                'previous' => $e->getPrevious(),
                'data' => [$name, $params]
            ],
            "сделане перезагрузка кеша",
            \App\Models\System\SystemLog::CODE_ERROR
        );
        Artisan::call("route:clear");

        try {
            return route($name, $params, false);
        } catch (\Throwable $e) {
            \App\Models\System\SystemLog::addLog(
                "Route не найдет перезагрузка кеша не дала результат",
                [
                    'data' => [$name, $params],
                    'massage' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile()." Line ",$e->getLine(),
                    'previous' => $e->getPrevious(),
                ],
                "Критическая ошибка Routr",
                \App\Models\System\SystemLog::CODE_ERROR
            );
             abort(500,$name." Данный путь не существует !!!!!! ");
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

function setTimestamps(array $data, string $type) {
    switch ($type) {
        case 'create':
            $data['created_at'] = date('Y-m-d h:i:s');
            break;
        case 'update':
            $data['updated_at'] = date('Y-m-d h:i:s');
            break;
    }
return $data;

}
