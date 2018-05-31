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
        $page = 'userList';
        $title = 'userList';

        $this->page_display($page, $title);
    }

    public function get_user_list(){
        $pageNum = $this->input->post('pageNum');
        $pageNum = intval($pageNum) > 0 ? intval($pageNum) : 1;
        $limit = 20;
        $offset = ($pageNum - 1) * $limit;

        $nextFlg = false;
        $userList = $this->userModel->get_user_list($offset, $limit);
        $count = count($userList);
        if($count == $limit){
            $offsetNext = $offset + $limit;
            $userListNext = $this->userModel->get_user_list($offsetNext, $limit);
            if(count($userListNext) > 0){
                $nextFlg = true;
            }
        }

        $data['userList'] = $userList;
        $data['nextFlg'] = $nextFlg;

        $this->ajax_return('200', 'success get user list!', $data);
    }

    public function user_opt($userId = null){
        $page = 'userOpt';
        $title = 'userAdd';

        // 如果存在用户ID，则为编辑或详情界面
        if($userId){
            $title = 'userDetail';
        }

        $this->assign('userId', $userId);
        $this->page_display($page, $title);
    }

    public function get_user_info(){
        $userId = $this->input->post('userId');

        $userInfo = [];
        if($userId){
            $redis = $this->redis_connect();
            $user_info = $redis->get('user_info_' . $userId);
            if(!empty($user_info))
            {
                $userInfo = json_decode($userInfo, true);
            }
            else
            {
                $userInfo = $this->userModel->get_user_info_by_id($userId);

                $redis->setex('user_info_' . $userId, 10, json_encode($userInfo));
            }
        }

        $this->ajax_return('200', 'success get user info!', $userInfo);
    }

    public function user_handle(){
        $userId = $this->input->post('userId');
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
        $userdata['updatedTime'] = time();

        if($userId){ // update
            $res = $this->userModel->update_user($userdata, $userId);
        }else{ // insert
            $userdata['createdTime'] = time();
            $res = $this->userModel->insert_user($userdata);
        }
        if($res){
            $this->ajax_return('200', 'User Add Success!', $userdata);
        }
        $errorMsg = $this->get_error_msg('error_user_add');
        $this->ajax_return('201', $errorMsg);
    }


    /* ========================================== 以下方法暂未使用 ========================================== */
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