<?php
use App\Models\System\SystemLog;
$includeFile =[
    __DIR__.'\app\Models\CoreEngine\Core\CodeValidation.php',
    __DIR__.'\app\Models\CoreEngine\Core\CoreEngine.php',
    __DIR__.'\app\Models\CoreEngine\Core\CoreParam.php',
];
var_dump(opcache_get_configuration());
echo "\n";
var_dump(opcache_get_status());
echo "\n";

for($index = 0; $index < count($includeFile);$index++){
    var_dump(opcache_compile_file($includeFile[$index]));
}