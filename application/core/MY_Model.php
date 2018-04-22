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

    /* 新增单条记录的数据
     * $tableName    String -- 需要操作的数据表名称
     * $data         array  -- 一维数组，包含需要添加到对应表中的记录信息数组
     * $res          bool   -- 根据成功或失败返回 TRUE 或 FALSE
     * */
    public function insert($tableName, $data){
        $data['createdTime'] = time();
        $data['updatedTime'] = time();
        $res = $this->db->insert($tableName, $data);

        return $res;
    }

    /* 新增批量记录的数据
     * $tableName    String -- 需要操作的数据表名称
     * $data         array  -- 二维数组，包含需要添加到对应表中的记录信息数组
     * $res          bool   -- 根据成功或失败返回 TRUE 或 FALSE
     * */
    public function insert_batch($tableName, $data){
        // 启用事务处理批量插入
        $this->db->trans_start();

        foreach($data as $key => $value){
            $value['createdTime'] = time();
            $value['updatedTime'] = time();

            $data[$key] = $value;
        }

        $this->db->insert_batch($tableName, $data, 'id');
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }

        return true;
    }

    /* 根据条件删除指定表单条数据
     * $tableName    String -- 需要操作的数据表名称
     * $where        array  -- 删除条件
     * $res          mixed  --
     * */
    public function delete($tableName, $where){
        $res = $this->db->delete($tableName, $where);

        return $res;
    }

    /* 根据条件删除指定表多条数据
     * $tableName        String -- 需要操作的数据表名称
     * $where            array  -- 删除条件
     * $field            String -- 批量删除条件字符串
     * $fieldValueArr    array  -- 批量删除条件对应的结果数组
     * $res              mixed  --
     * */
    public function delete_batch($tableName, $where, $field, $fieldValueArr){
        $this->db->where($where);
        $this->db->where_in($field, $fieldValueArr);
        $res = $this->db->delete($tableName);

        return $res;
    }

    /* 更新指定表的单条数据
     * $tableName    String -- 需要操作的数据表名称
     * $data         array  -- 需要更新的数据数组
     * $where        array  -- 数据更新条件
     * $res          bool   --
     * */
    public function update($tableName, $data, $where){
        $data['updatedTime'] = time();

        $res = $this->db->update($tableName, $data, $where);

        return $res;
    }

    /* 更新指定表的单条数据
     * $tableName    String -- 需要操作的数据表名称
     * $data         array  -- 需要更新的数据数组
     * $field        String -- 数据更新条件（$data中的某一字段）
     * $res          bool   --
     * */
    public function update_batch($tableName, $data, $field){
        // 启用事务处理批量插入
        $this->db->trans_start();

        foreach($data as $key => $value){
            $value['updatedTime'] = time();

            $data[$key] = $value;
        }

        $this->db->update_batch($tableName, $data, $field);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }

        return true;
    }

}