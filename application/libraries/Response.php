<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/15
 * Time: 下午2:24
 */
class Response {
    /* 按照接口需求的数据格式返回数据
     * @param string $type 接口需求的数据格式
     * @param int $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     * */
    public function responseData($type = 'json', $code = 200, $message = '', $data = []){
        $type = $type ? strtolower($type) : 'json';
        $data = $data ? $data : [];

        if(!is_numeric($code)){
            return false;
        }
        if(!is_array($data)){
            return false;
        }

        $reData = [];
        $reData['type'] = $type;
        $reData['code'] = $code;
        $reData['message'] = $message;
        $reData['result'] = $data;

        $return = '';

        if($type == 'json'){
            $return = self::getJsonData($reData);
        }

        if($type == 'xml'){
            $return = self::getXmlData($reData);
        }

        return $return;
    }

    /* 按照Json格式返回通信数据
     * @param array $data 数据
     * return string （json格式）
     * */
    public static function getJsonData($data){
        return json_encode($data);
    }

    /* 按照Xml格式返回通信数据
     * @param array $data 数据
     * return string （xml格式）
     * */
    public static function getXmlData($data){
        header('Content-Type:text/xml');

        $xml = "<?xml version='1.0' encoding='UTF-8' ?>\n";
        $xml .= "<root>";
        $xml .= self::xmlToEncode($data);
        $xml .= "</root>\n";

        return $xml;
    }

    /* 组装xml数据字符串
     * @param array $data 数据
     * return string （部分xml节点字符串）
     * */
    public static function xmlToEncode($data){
        $xml = $attr = "";
        foreach($data as $key => $value){
            if(is_numeric($key)){
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value;
            $xml .= "</{$key}>\n";
        }

        return $xml;
    }
}