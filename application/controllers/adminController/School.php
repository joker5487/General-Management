<?php
/**
 * Created by PhpStorm.
 * User: joker.chen
 * Date: 2018/4/22
 * Time: 22:36
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

class School extends Admin_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel/schoolModel');
    }

    public function show_page(){
        $page = 'school';
        $title = 'school';

        $this->page_display($page, $title);
    }

    public function add_school_info(){
        $schoolList = $this->input->post('schoolList');
        $delIds = $this->input->post('delIds');

        $res = 'no operation';

        if(!empty($delIds)){
            $res = $this->schoolModel->delete_data($delIds);
        }

        if(!empty($schoolList)){
            $res = $this->schoolModel->insert_batch_data($schoolList);
        }

        $this->ajax_return('200', 'success get school list!', $res);
    }

    public function get_school_data(){
        $schoolList = $this->schoolModel->get_data();

        $this->ajax_return('200', 'success get school list!', $schoolList);
    }
}