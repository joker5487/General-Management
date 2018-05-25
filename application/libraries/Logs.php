<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/21
 * Time: 下午1:44
 */
class Logs {
    private $_dir;
    private $_ext;
    private $_error;

    public function __construct(){
        $this->_dir = FCPATH . 'application/logs/';
        $this->_ext = '.txt';
        $this->_error = 'error_';
    }

    /* 向文件中写入日志信息
     * @param string $path 文件夹路径
     * @param int $formatType 日志格式类型（值为 1, 表示每天单独一个日志文件，格式为 error_20180525.txt, 值不为 1, 表示所有日志在一个文件中, 格式为 error_logs.txt）
     * @param string $logType 日志信息级别（格式： "error"、"debug" 等）
     * @param string $message 日志信息
     * return
     * */
    public function logWrite($path, $formatType, $logType, $message){
        $logDir = $this->_dir . $path;

        if(!is_dir($logDir)){
            mkdir($logDir, 0777);
        }

        if(!is_numeric($formatType)){
            $formatType = 0;
        }

        $format = self::logFormat($formatType);
        $log = sprintf($format, date('Y/m/d h:i:s'), $logType, $message);

        switch($formatType)
        {
            case 1:
                $errorFileName = $this->_error . date('Ymd');
                break;
            default:
                $errorFileName = $this->_error . 'logs';
                break;
        }

        $errorPath = $logDir . $errorFileName . $this->_ext;
        $res = file_put_contents($errorPath, $log, FILE_APPEND);

        return $res;
    }

    /* 读取文件中的日志信息
     * @param string $path 文件夹路径
     * @param int $logFileName 日志文件名称
     * return string 日志文件信息
     * */
    public function logRead($path, $logFileName){
        $logDir = $this->_dir . $path;
        if(!is_dir($logDir)){
            return false;
        }

        $logFile = $logDir . $logFileName . $this->_ext;
        if(!is_file($logFile)){
            return false;
        }

        $logInfo = file_get_contents($logFile);
        return $logInfo;
    }

    /* 日志内容格式化
     * 说明：可根据自己需要随意增加格式，增加格式的同时增加参数做标识
     * */
    public static function logFormat(){
        $format = "%s [%s] : %s";
        $format .= " \n";

        return $format;
    }
}