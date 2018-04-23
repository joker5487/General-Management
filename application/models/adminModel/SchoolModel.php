<?php
/**
 * Created by PhpStorm.
 * User: joker.chen
 * Date: 2018/4/22
 * Time: 22:34
 */

class SchoolModel extends MY_Model
{
    public function insert_batch_data($data){
        $res = $this->insert_batch('school', $data);
        return $res;
    }

    public function get_data(){
        $schoolList = $this->db->select('*')
            ->from('school')
            ->get()->result_array();

        return $schoolList;
    }

    public function delete_data($delIds){
        $res = $this->delete_batch('school', ['userId != ""'], 'id', $delIds);
        return $res;
    }
}