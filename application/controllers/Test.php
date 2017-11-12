<?php
/**
 * Created by PhpStorm.
 * User: joker
 * Date: 2017/11/12
 * Time: 15:15
 */

class Test extends MY_Controller
{
    public function index(){
        $this->assign('describe', '这个控制器及其对应的页面均为系统测试页面！！！');
        $this->assign('testKey001', 'testVal001');
        $this->display(APPPATH . 'views/templates/test.html');
    }
}