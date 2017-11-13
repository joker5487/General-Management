<?php
/**
 * Created by PhpStorm.
 * User: joker
 * Date: 2017/11/12
 * Time: 15:15
 */

class Test extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $layout = [];
        $layout['page'] = 'test';
        $layout['title'] = 'Page1';
        $layout['asideMenu'] = config_item('asideMenu');
        $this->assign('layout', $layout);

        $data = [];
        $data['describe'] = '这个控制器及其对应的页面均为系统测试页面！！！';
        $data['testKey001'] = 'testVal001';
        $this->assign('data', $data);

        $this->display(APPPATH . 'views/templates/admin/layout.html');
    }
}