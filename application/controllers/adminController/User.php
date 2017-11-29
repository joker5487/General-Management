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

        echo $this->get_error_msg('error_user_name');die;

        $this->display(ADMIN_LAYOUT_PATH);
    }

    public function get_user_list(){
        $pageNum = $this->input->post('pageNum');
        $pageNum = intval($pageNum) > 0 ? intval($pageNum) : 1;
        $limit = 20;
        $offset = ($pageNum - 1) * $limit;

        $userList = $this->userModel->get_user_list($offset, $limit);

        $this->ajax_return('200', $userList, 'success get user list!');
    }

    public function user_add(){
        $userName = $this->input->post('userName');
        $realName = $this->input->post('realName');
        $headImage = $this->input->post('headImage');
        $email = $this->input->post('email');
        $phoneNumber = $this->input->post('phoneNumber');
        $password = $this->input->post('password');
        $roleId = $this->input->post('roleId');

        if(empty($userName)){
            $errorMsg = $this->get_error_msg('error_user_name');
            $this->ajax_return('201', $errorMsg);
        }
        if(empty($password)){
            $errorMsg = $this->get_error_msg('error_user_password');
            $this->ajax_return('201', $errorMsg);
        }

        if(empty($realName)){
            $realName = $userName;
        }
        if(empty($headImage)){
            $headImage = PUBLIC_IMG_RESOURCE_PATH . 'default-user.jpg';
        }
        if(empty($roleId)){
            $roleId = 1;
        }

        $userdata['userName'] = $userName;
        $userdata['realName'] = $realName;
        $userdata['headImage'] = $headImage;
        $userdata['email'] = $email;
        $userdata['phoneNumber'] = $phoneNumber;
        $userdata['password'] = $password;
        $userdata['roleId'] = $roleId;
        $userdata['status'] = 1;

        $res = $this->userModel->insert_user($userdata);
        if($res){
            $this->ajax_return('200');
        }
        $errorMsg = $this->get_error_msg('error_user_add');
        $this->ajax_return('201', $errorMsg);
    }
}