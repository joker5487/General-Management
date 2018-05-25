<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/25
 * Time: 下午1:50
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

class Logs extends Admin_Controller
{
    public function __construct(){
        $this->load->library('logs');
    }

    public function system_logs(){
        $page = 'systemLogs';
        $title = 'systemLogs';

        $this->page_display($page, $title);
    }

    public function get_system_logs(){
        $logs = new Logs();

        $logPath = 'logtest/';
        $logFileName = 'error_logs';
        $logsInfo = $logs->logRead($logPath, $logFileName);

        $this->ajax_return('200', 'get system logs success.', $logsInfo);
    }
}