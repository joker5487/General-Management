<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/14
 * Time: 下午3:17
 */
class Admin_Controller extends MY_Controller{

    public function __construct(){
        parent::__construct();
    }

    public function P($data, $type = NULL){
        echo '<pre>';
        if(empty($type)){
            var_dump($data);
        }else{
            print_r($data);
        }
        echo '</pre>';
    }

    public function ajax_return($status = '200', $data = array(), $msg = ''){
        $return = [];
        $return['status'] = $status;
        $return['data'] = $data;
        $return['msg'] = $msg;

        echo json_encode($return);
        exit;
    }

}