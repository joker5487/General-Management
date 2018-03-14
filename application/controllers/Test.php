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
        $data = [];
        $data['describe'] = '测试CI框架整合Smarty模版引擎！！！';
        $this->assign('data', $data);

        $this->display(APPPATH . 'views/templates/test.tpl');
    }
}