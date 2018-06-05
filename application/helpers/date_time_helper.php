<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/6/5
 * Time: 9:49
 */

if ( ! function_exists('date_time_processing') ) {

    /* 日期时间格式转换处理
     *
     * @param mix       $date       需要转换的字符串(格式：时间戳 1528182856 ; 字符串格式："2018-06-05 09:14:16" 等)
     * @param string    $format     需要转换的格式
     * return mix                   转换后的字符串(时间戳或者date格式)
     **/
    function date_time_processing($date = NULL, $format = NULL){
        if(is_numeric($date) && strtotime(strtotime($date)) !== FALSE)
        {
            $dateTime = $date;
        }
        else
        {
            $dateTime = strtotime($date) ? strtotime($date) : 0;
        }

        // 如果没有格式要求，直接按照时间戳格式返回
        if(empty($format))
        {
            return $dateTime;
        }

        // 按照给定的时间格式返回数据
        return date($format, $dateTime);
    }

}