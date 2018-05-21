<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/21
 * Time: 下午1:44
 */
class Log {
    private $_dir;
    private $_ext;
    private $_access;
    private $_error;

    public function __construct(){
        $this->_dir = FCPATH . 'application/log/';
        $this->_ext = '.txt';
        $this->_access = 'access';
        $this->_error = 'error';
    }

    public function logWrite($path, $formatType, $logType, $message){
        $logDir = $this->_dir . $path;

        if(!is_dir($logDir)){
            mkdir($logDir, 777);
        }

        if(!is_numeric($formatType)){
            $formatType = 0;
        }

        $format = self::logFormat($formatType);

        switch($formatType)
        {
            case 1:
                $log = sprintf($format, $logType, $message);
                $errorFileName = $this->_error . date('Ymd');
                break;
            default:
                $log = sprintf($format, date('Y/m/d h:i:s'), $logType, $message);
                $errorFileName = $this->_error;
                break;
        }

        $errorPath = $logDir . $errorFileName . $this->_ext;
        $res = file_put_contents($errorPath, $log, FILE_APPEND);
    }

    public static function logFormat($formatType){
        $defaultFormat = "%s [%s] : %s \n";

        switch($formatType)
        {
            case 1:
                $format = "[%s] : %s";
                break;
            default:
                $format = $defaultFormat;
                break;
        }

        return $format;
    }
}