<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 17.12.2022
 * Time: 0:15
 */

namespace App\Models\System\General;


use phpDocumentor\Reflection\Types\This;
use stringEncode\Exception;

class TreeBuild {

    const ROOT__ = 0;

    private $data;
    private $tree;
    private $listPaht;

    private $listPath = [];
    private $listLevel;

    public function setData($data){
        if(is_array($data)) {
           if(key_exists(0,$data)) {
               if(key_exists('id',$data[0]) && key_exists('parent_id',$data[0])){
                   $this->data = $data;
               }else{
                    throw new Exception('Dosn`t exist key (id,parent_id)');
               }
           }
        }
        return $this;
    }
    public function getTree(){
        $this->tree = $this->nodeTree($this->data);
        return $this->tree;
    }

    public function getLevelCount(){
        $this->listLevel;
    }

    public function  getAllPath(){
        return $this->listPath;
    }
    public function  getPath($id){
        return $this->listPaht[$id];
    }
    public function getChankTree($id){
        $this->tree = $this->nodeTree($this->data,$id);
        return $this->tree;
    }

    private function nodeTree($data,$id = 0,$level= 0,$path = []){
        $result = [];
        $level+=1;
        if($id != 0) $path[] = $id;
        foreach ($data as $item){
            if ($item['parent_id'] == $id  &&  $item['parent_id'] != $item['id']) {
                $children = $this->nodeTree($data,$item['id'],$level,$path);
                if(count($children) == 0)$children = null;
                $this->listPath[$item['id']] = array_merge($path,[$item['id']]);
                $result[] = ['id'=>$item['id'],'item' => $item,'path'=>array_merge($path,[$item['id']]),'children' =>$children,'level'=>$level];
                if($item['parent_id'] == 0)$path = [];
            }
            $this->listLevel[$level] = $level;
        }
        return $result;
    }
}