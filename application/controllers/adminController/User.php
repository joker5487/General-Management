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
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function user_list(){
        $page = 'userList';
        $title = 'userList';

        $this->page_display($page, $title);
    }

    public function get_user_list(){
        $pageNum = $this->input->post('pageNum');
        $pageNum = intval($pageNum) > 0 ? intval($pageNum) : 1;
        $limit = 20;
        $offset = ($pageNum - 1) * $limit;

        $userList = $this->userModel->get_user_list($offset, $limit);

        $this->ajax_return('200', 'success get user list!', $userList);
    }

    public function user_opt($userId = null){
        $page = 'userOpt';
        $title = 'userAdd';

        // 如果存在用户ID，则为编辑或详情界面
        if($userId){
            $title = 'userDetail';
        }

        log_message('info', 'test log message!');
        $this->page_display($page, $title);
    }

    public function get_user_info($userId = null){
//        $userId = $this->input->get('userId');
//        $userInfo = $this->userModel->get_user_info_by_id($userId);

        $this->ajax_return('200', 'success get user info!', $userId);
    }

    public function user_handle(){
        $userName = $this->input->post('userName');
        $realName = $this->input->post('realName');
        $headImage = $this->input->post('headImage');
        $email = $this->input->post('email');
        $phoneNumber = $this->input->post('phoneNumber');
        $password = $this->input->post('password');
        $roleId = $this->input->post('roleId');
        $status = $this->input->post('status');

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
        $userdata['status'] = $status;
        $userdata['createdTime'] = time();
        $userdata['updatedTime'] = time();

        $res = $this->userModel->insert_user($userdata);
        if($res){
            $this->ajax_return('200', 'User Add Success!', $userdata);
        }
        $errorMsg = $this->get_error_msg('error_user_add');
        $this->ajax_return('201', $errorMsg);
    }

    // 暂时未使用
    public function get_files(){
        $folderPath = PUBLIC_IMG_RESOURCE_PATH . 'test/';
        //读取文件列表
        $file = [];
        $dir = $folderPath;
        if (!is_dir($dir)) {
            return $file;
        }
        $handle = opendir($dir);
        while ($file_name = readdir($handle)) {
            if ($file_name != '.' && $file_name != '..') {
                if (is_file($dir.'/'.$file_name)) {
                    $infoArr = explode('.', $file_name);
                    $file_info = array();
                    $file_info['name'] = $file_name;
                    $file_info['ext'] = strtolower(end($infoArr));
                    array_push($file, $file_info);
                }
            }
        }
        closedir($handle);

        $this->P($file);
    }
}