<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/14
 * Time: 下午3:21
 */
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

class Admin_Test extends Admin_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
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

        $this->display(APPPATH . 'views/templates/admin/layout.html');
    }
}