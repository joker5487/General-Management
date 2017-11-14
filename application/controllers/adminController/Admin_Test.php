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
        $data = ['key1' => 'val1', 'key2' => 'val2'];
        $this->P($data);
    }
}