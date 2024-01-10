<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10.01.2023
 * Time: 19:50
 */
namespace App\Models\System;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class buildTreeFromDirectory{
    public  $root_dir,
        $file_prefix,
        $allowed_extensions,
        $ignore_hidden,
        $exclude_dir;

    private $filter;

    public function __construct($root_dir){
        $t = $root_dir;
        $root_dir = realpath($root_dir);
        if(!$root_dir) {
            dd($t);
            throw new \Exception("This directory doesn't exist! " . $root_dir);
        }
        $this->root_dir = $root_dir;
        $this->setFilter();
        date_default_timezone_set(@date_default_timezone_get());
    }
    public function setFilter($allowed_extensions = array('*'), $file_prefix = '', $exclude_dir = [], $ignore_hidden = true){
        $this->allowed_extensions = $allowed_extensions;
        $this->file_prefix = $file_prefix;
        $this->ignore_hidden = $ignore_hidden;
        $this->exclude_dir = $exclude_dir;
    }
    public function buildTree(){
        $tree = [];
        $dir = new RecursiveDirectoryIterator($this->root_dir, \FilesystemIterator::SKIP_DOTS);

        $this->filter($dir);
        $it = new \RecursiveIteratorIterator($this->filter, \RecursiveIteratorIterator::SELF_FIRST, \RecursiveIteratorIterator::CATCH_GET_CHILD);

        foreach($it as $fileinfo) {
            $name = $fileinfo->getFilename();
            $sub_path_name = $it->getSubPathName();
            $parts = explode(DIRECTORY_SEPARATOR, $sub_path_name);
            array_pop($parts);
            $parentArr = &$tree;
            //go deep in the file|dir path
            foreach ($parts as $part)
                $parentArr = &$parentArr['dirs'][$part];

            if ($fileinfo->isDir()) {
                // Add the final part to the structure
                $parentArr['dirs'][$name] = array('folder' => $name);
            } else {
                // Add some file info to the structure

                if($fileinfo->isLink()){
                    $realpath = $fileinfo->getRealPath();
                    $filesize = filesize($realpath);
                    $filemtime = filemtime($realpath);
                } else {
                    $filesize = $fileinfo->getSize();
                    $filemtime = $fileinfo->getMTime();
                }

                $parentArr['files'][] = array(
                    'filename'          => $name,
                    'filesize'          => $this->fileSizeConvert($filesize),
                    'date'              => date("d-m-Y H:i", $filemtime),
                    'relative_path'     => $it->getSubPath()
                );
            }
        }
        unset($parentArr);
        $this->sortArray($tree);
        return $tree;
    }
    private function sortArray(&$tree){
        foreach ($tree as &$value) {
            if (is_array($value))
                $this->sortArray($value);
        }
        return ksort($tree);
    }
    public function getTree(){
       return $this->buildTree();
    }
    private function filter($dir){
        $this->filter = new \RecursiveCallbackFilterIterator($dir,
            function($current, $key, $iterator){
                $filename = $current->getFilename();
                //ignore all hidden files/directories
                if($this->ignore_hidden){
                    if(substr($filename, 0, 1) == '.')
                        return false;
                }
                // Allow recursion
                if($iterator->hasChildren() && !in_array($filename, $this->exclude_dir))
                    return true;

                if($current->isReadable() === false)
                    return false;
                //filter by file extension
                $path = $current->getPathname();
                $file_ext = pathinfo($path, PATHINFO_EXTENSION);
                $ext_allowed =($this->allowed_extensions[0] == '*')? true:in_array($file_ext, $this->allowed_extensions);

                if(!empty($this->file_prefix)){
                    //filter by prefix and extension
                    return(strpos($filename, $this->file_prefix) === 0 && $ext_allowed)?true:false;
                }
                //filter by extension
                return $ext_allowed;
            }
        );
    }
    public function fileSizeConvert($bytes){
        $label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for($i = 0; $bytes >= 1024 && $i < (count($label) -1); $bytes /= 1024, $i++);
        return round($bytes, 2) . " " . $label[$i];
    }
}
