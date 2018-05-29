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

    // 初始化默认路径
    public function __construct(){
        $this->_dir = FCPATH;
    }

    /* 获取指定目录下面的文件夹和文件
     * @param string $path 指定的文件夹目录
     * @param string $sort 文件排序标识
     * @param string $filed 文件排序信息标识(目前只有 name - 文件名称 filectime - 创建时间 filemtime - 修改时间)
     * @param int $sortType 文件排序类型(1 分类排序 或 0 混合排序)
     * @param bool $isHidden 显示标识，是否显示隐藏文件(false 为不显示，true 为显示)
     * */
    public function get_documents($path = '', $sort = 'desc', $filed = 'filemtime', $sortType = 0, $isHidden = false){
        $folders = [];
        $files = [];

        $dir = $this->_dir . $path;
        $sort = strtolower($sort);

        // 如果给定的路径是文件夹，则获取该路径下的所有文件夹和文件(获取名称、创建时间、修改时间)
        if(is_dir($dir)){
            if($dh = opendir($dir)){
                while(($file = readdir($dh)) !== FALSE){
                    // 判断是否需要显示隐藏文件
                    if((!$isHidden && !preg_match('/^\./', $file)) || ($isHidden && !in_array($file, ['.', '..']))){
                        $newFile = $dir . DIRECTORY_SEPARATOR . $file;
                        if((is_dir($newFile))){
                            $folderInfo['type'] = 'folder';
                            $folderInfo['name'] = $file;
                            $folderInfo['filectime'] = stat($newFile)['ctime']; // 创建时间
                            $folderInfo['filemtime'] = stat($newFile)['mtime']; // 修改时间

                            $folders[] = $folderInfo;
                        }else{
                            $fileInfo['type'] = 'file';
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

        // 对该路径下面的文件进行分类排序
        if($sort === 'desc'){
            $sortOder = SORT_DESC;
        }else{
            $sortOder = SORT_ASC;
        }

        $documents = [];
        // 混合排序
        if(!$sortType){
            $mixFiles = array_merge($folders, $files);
            if(!empty($mixFiles)){
                $documents = $this->my_sort($mixFiles, $filed, $sortOder);
            }
        }
        // 分类排序
        else{
            if(!empty($folders)){
                $folders = $this->my_sort($folders, $filed, $sortOder);
                $documents['folders'] = $folders;
            }
            if(!empty($files)){
                $files = $this->my_sort($files, $filed, $sortOder);
                $documents['files'] = $files;
            }
        }

        return $documents;
    }

    /* 二维数组排序(此方法可以提成一个公共的helper)
     * @param array $arrays 需要进行排序的二维数组
     * @param string $sortKey 排序字段
     * @param string $sortOrder 排序标识
     * @param string $sortType 排序类型标识
     * */
    function my_sort($arrays, $sortKey, $sortOrder = SORT_DESC, $sortType = SORT_REGULAR){
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $keyArrays[] = $array[$sortKey];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($keyArrays, $sortOrder, $sortType, $arrays);
        return $arrays;
    }
}