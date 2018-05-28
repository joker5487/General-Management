<?php
/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/28
 * Time: 上午10:10
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('url_format'))
{
    /**
     * 指定url格式化
     *
     * @param	string	$url 需要格式化的路径
     * @param	string	$urlType 传入的路径类型(1 - 文件夹，0 - 非文件夹)
     * @return	string
     */


    function url_format($url = '', $urlType = 1){
        if(empty($url)){
            return '';
        }

        $urlPrefix = '';
        $urlSuffix = $url;
        // 检测是否是网络路径(以 http 或 https 开头的路径)
        if(preg_match('/(http:\/\/)|(https:\/\/)/i', $url)){
            $urlArr = explode('://', $url);
            $urlPrefix = $urlArr[0];
            $urlSuffix = $urlArr[1];
        }

        // 将多余的 '/' 替换成一个
        $urlSuffix = str_replace('//', '/', $urlSuffix);

        if(!empty($urlPrefix)){
            $newUrl = trim($urlPrefix . '://' . $urlSuffix);
        }else{
            $newUrl = trim($urlSuffix);
        }

        // 如果是文件夹路径
        if($urlType == 1){
            $ending = substr($newUrl, -1);
            if($ending !== '/'){
                $newUrl .= '/';
            }
        }

        return $newUrl;
    }
}