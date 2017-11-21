<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/20
 * Time: 下午4:40
 */
class UserModel extends MY_Model
{
    public function get_user_list(){
        $userList = $this->db->select('*')
                    ->from('user_admin')
                    ->get()->result_array();

        return $userList;
    }
}