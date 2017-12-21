<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/12/14
 * Time: 下午4:54
 */
class Upload extends Admin_Controller {
    public function __construct () {
        parent::__construct();
        $this->load->model('adminModel/userModel');
    }

    public function uploadFile(){
        $this->set_upload_params();

        $up = 'file';
        if($this->upload->do_upload($up)){
            $uploadInfo = $this->upload->data();
            $this->ajax_return('200', 'SUCCESS', $uploadInfo);
        }else{
            $arr = [];
            $arr['data'] = $this->upload->data();
            $arr['error'] = $this->upload->display_errors();
            $this->ajax_return('000', 'ERROR', $arr);
        }
    }

    public function set_upload_params(){
        $config = array();
        $config['upload_path'] =  PUBLIC_IMG_RESOURCE_PATH . 'test/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 20480;

        $this->load->library('upload', $config);
    }
}