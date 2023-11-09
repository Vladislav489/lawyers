<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.09.2022
 * Time: 17:54
 */

namespace App\Models\CoreEngine\Model\InterfacModel;


interface InterfaceModyfiModel{
    public function getLable();
    public static function removeNotExistCol_($array);
}
