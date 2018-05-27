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

    public function get_documents($path = '', $sort = 'desc', $filed = 'filemtime'){
        $folders = [];
        $files = [];

        $dir = $this->_dir . $path;
        $sort = strtolower($sort);

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

        if($sort === 'desc'){
            $sortOder = SORT_DESC;
        }else{
            $sortOder = SORT_ASC;
        }

        $folders = $this->my_sort($folders, $filed, $sortOder);
        $files = $this->my_sort($files, $filed, $sortOder);

        $documents['folders'] = $folders;
        $documents['files'] = $files;

        echo '<pre>';
        var_dump($documents);
        return $documents;
    }

    function my_sort($arrays, $sort_key, $sort_order = SORT_DESC, $sort_type = SORT_REGULAR){
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_arrays,$sort_order,$arrays);
        return $arrays;
    }
}