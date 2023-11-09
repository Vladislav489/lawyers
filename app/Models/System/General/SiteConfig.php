<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.02.2023
 * Time: 17:59
 */

namespace App\Models\System\General;


class SiteConfig{
    private $path ="/config/";
    private $fileName ='site.php';
    private $fullPath = "";
    private $name = "site";

    public function __construct(){
        $this->getFullPath();
    }
    public function setConfig($items){
        $config = [];
        if(file_exists($this->fullPath)){
            $config = $config = include $this->getFullPath();
            config([$this->name => array_merge($config,$items)]);
        }else{
            config([$this->name => $items]);
        }
        $fp = fopen($this->getFullPath() , 'w');
        fwrite($fp, '<?php return ' . var_export(config($this->name), true) . ';');
        fclose($fp);
    }

    public function getConfig($name){
        $config = include $this->getFullPath();
        if(isset($config[$name]))
            return $config[$name];
        else
            return false;
    }



    public function getFullPath(){
        return $this->fullPath =  base_path().$this->path.$this->fileName;
    }
}