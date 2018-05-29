<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/14
 * Time: 下午3:21
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

class Admin_Test extends Admin_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        var_dump(intval(000000));
        exit;

        $layout = [];
        $layout['page'] = 'test';
        $layout['title'] = 'Page1';
        $layout['asideMenu'] = config_item('asideMenu');
        $layout['base_url'] = base_url();
        $this->assign('layout', $layout);

        $data = [];
        $data['describe'] = '这个控制器及其对应的页面均为系统测试页面！！！';
        $data['testKey001'] = 'testVal001';
        $this->assign('data', $data);

        $this->display(ADMIN_LAYOUT_PATH);
    }

    public function redis(){
        $redis = new Redis();

        $redis->connect('127.0.0.1');
        $redis->set('mm', '123123');

        echo $redis->get('mm');
    }

    public function responseData(){
        $this->load->library('response');
        $response = new Response();

        $format = $this->input->get('format');
        $format = $format ? strtolower($format) : 'json';

        $arr = array(
            'aa' => 1212,
            'bb' => 2323,
            'cc' => array(3,4,5)
        );
        $data = $response->responseData($format, 200, 'success', $arr);
        echo $data;
    }

    public function logWrite(){
        $this->load->library('logs');
        $logs = new Logs();

        $res = $logs->logWrite('logtest/', 1, 'test1', 'This is log test');
        var_dump($res);
    }

    public function logRead(){
        $this->load->library('logs');
        $logs = new Logs();

        $res = $logs->logRead('logtest/', 'error_logs');
        var_dump($res);
    }

    public function document_folder(){
        $this->load->library('document');
        $document = new Document();

        $files = $document->get_documents();

        echo "<pre>";
        var_dump($files);
    }

    public function url_format(){
        $this->load->helper('url_format');

        $url = 'http://aaaa//sdsad/adads/dss/sds.txt';
        $newUrl = url_format($url, 0);

        var_dump($newUrl);
    }
}