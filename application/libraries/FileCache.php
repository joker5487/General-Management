<?php
/**
 * Created by PhpStorm.
 * User: joker.chen
 * Date: 2018/5/15
 * Time: 23:42
 */

class FileCache {
    private $_dir;
    private $_ext;

    public function __construct(){
        $this->_dir = FCPATH . 'application/cache/';
        $this->_ext = '.txt';
    }

    public function createFileCache($path = '', $fileName = '', $data = [], $cacheTime = 0){
        $dir = $this->_dir . $path;
        $flg = self::createDir($dir);
        if(!$flg){
            echo 'create dir failed.';
            return false;
        }

        $filePath = $dir . $fileName . $this->_ext;
        // 缓存文件存在
        if(is_file($filePath)){
            // 判断缓存文件是否过期
            $cacheFlg = self::checkCacheTime($filePath);
            if(!$cacheFlg){ // 没过期
                return true;
            }else{ // 已过期
                unlink($filePath);
                return false;
            }
        }

        // 文件不存在
        file_put_contents($filePath, $cacheTime . json_encode($data));

        // 待优化 cacheTime = 0 的情况等等
    }

    public static function createDir($dir){
        if(is_dir($dir)){
            return true;
        }

        $flg = mkdir($dir, 777);
        if(!$flg){
            return false;
        }

        return true;
    }

    public static function checkCacheTime($filePath){
        $fileInfo = file_get_contents($filePath);

        $createFileTime = filectime($filePath);
        $cacheTime = intval(substr($fileInfo, 0, 11));

        if(($createFileTime + $cacheTime) > time()){
            return true;
        }

        return false;
    }
}