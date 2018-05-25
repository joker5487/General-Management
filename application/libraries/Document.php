<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/25
 * Time: 下午2:02
 */
class Document
{
    private $_dir;

    public function __construct(){
        $this->_dir = FCPATH . 'application/logs/logtest/';
    }

    public function get_folders($path = ''){
        $folders = [];
        $files = [];

        $dir = $this->_dir . $path;

        if(is_dir($dir)){
            if($dh = opendir($dir)){
                while(($file = readdir($dh)) !== FALSE){

                    if($file != "." && $file != ".."){
                        $newFile = $dir . DIRECTORY_SEPARATOR . $file;
                        if((is_dir($newFile))){
                            $folderInfo['name'] = $file;
                            $folderInfo['filectime'] = stat($newFile)['ctime']; // 创建时间
                            $folderInfo['filemtime'] = stat($newFile)['mtime']; // 修改时间

                            $folders[] = $folderInfo;
                        }else{
                            $fileInfo['name'] = $file;
                            $fileInfo['filectime'] = filectime($newFile); // 创建时间
                            $fileInfo['filemtime'] = filemtime($newFile); // 修改时间

                            $files[] = $fileInfo;
                        }
                    }
                }
                closedir($dh);
            }
        }

        $documents['folders'] = $folders;
        $documents['files'] = $files;

        echo '<pre>';
        var_dump($documents);
        return $documents;
    }

    public function get_files(){

    }
}