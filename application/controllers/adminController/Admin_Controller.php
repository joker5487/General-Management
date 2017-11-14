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

}