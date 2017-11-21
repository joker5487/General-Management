<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/20
 * Time: 下午4:41
 */
class MY_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
}