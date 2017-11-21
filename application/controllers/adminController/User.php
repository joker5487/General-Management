<?php
/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/20
 * Time: 下午4:17
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

class User extends Admin_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel/userModel');
    }

    public function user_list(){
//        phpinfo();exit;
        $layout = [];
        $layout['page'] = 'userList';
        $layout['title'] = 'userList';
        $layout['asideMenu'] = config_item('asideMenu');
        $layout['base_url'] = base_url();
        $this->assign('layout', $layout);

        $userList = $this->userModel->get_user_list();
        $data['userList'] = $userList;
        $this->assign('data', $data);

        $this->P($data);exit;

        $this->display(LAYOUT_PATH);
    }
}