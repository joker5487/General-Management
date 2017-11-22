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

    public function insert_user($userInfo){
        $res = $this->db->insert('user_admin', $userInfo);

        return $res;
    }

    public function update_user($userInfo, $userId){
        $res = $this->db->update('user_admin', $userInfo, ['id' => $userId]);

        return $res;
    }

    public function delete_user($userId){
        $res = $this->db->delete('user_admin', ['id' => $userId]);

        return $res;
    }
}