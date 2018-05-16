<?php
/**
 * Created by PhpStorm.
 * User: joker.chen
 * Date: 2018/5/15
 * Time: 23:42
 */

/* 缓存文件生成规则
 * 缓存内容包括：有效时间（11位数字，以秒为单位 【如：3分钟表示为 00000000180】）+ 缓存数据字符串
 * */
class FileCache {
    private $_dir;
    private $_ext;

    public function __construct(){
        $this->_dir = FCPATH . 'application/cache/';
        $this->_ext = '.txt';
    }

    /* 获取缓存文件信息
     * @param string $path 文件夹路径
     * @param string $fileName 文件名称
     * */
    public function getFileCache($path = '', $fileName = ''){
        $dir = $this->_dir . $path;
        $filePath = $dir . $fileName . $this->_ext;

        // 缓存文件不存在
        if(!is_file($filePath)){
            return false;
        }

        // 判断缓存文件是否过期
        $cacheFlg = self::checkCacheTime($filePath);
        if(!$cacheFlg){ // 已过期
            unlink($filePath);
            return false;
        }

        // 未过期
        $fileInfo = file_get_contents($filePath);
        $contents = substr($fileInfo, 11);

        return $contents;
    }

    /* （在文件不存在的情况下）创建缓存文件
     * @param string $path 文件夹路径
     * @param string $fileName 文件名称
     * @param array 缓存数据
     * @param int 缓存有效时间，单位 秒
     * */
    /* 逻辑说明
     * 1、检查给定的文件夹路径是否存在
     * 1-1、如果不存在，则创建文件夹，并赋权限
     * 1-2、如果已存在，则检查文件是否存在
     * 1-2-1、如果不存在，则创建生成缓存文件
     * 1-2-2、如果已存在，则检查缓存文件是否过期
     * 1-2-2-1、如果未过期，则直接返回 true
     * 1-2-2-2、如果已过期，则删除该缓存文件，返回 true
     * */
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
            if(!$cacheFlg){ // 已过期
                unlink($filePath);
            }

            return true;
        }

        // 文件不存在，创建文件
        file_put_contents($filePath, $cacheTime . json_encode($data));
        return true;
    }

    /* 创建文件夹
     * @param string $dir 文件夹路径
     * return bool
     * */
    public static function createDir($dir){
        if(is_dir($dir)){
            return true;
        }

        $flg = mkdir($dir, 0777);
        if(!$flg){
            return false;
        }

        return true;
    }

    /* 检查缓存文件是否过期
     * @param string $filePath 缓存文件的路径
     * return
     * */
    public static function checkCacheTime($filePath){
        $fileInfo = file_get_contents($filePath);

        $createFileTime = filectime($filePath);
        $cacheTime = intval(substr($fileInfo, 0, 11));

        // 永久有效
        if($cacheTime === 0){
            return true;
        }

        // 未过期
        if(($createFileTime + $cacheTime) > time()){
            return true;
        }

        // 已过期
        return false;
    }
}