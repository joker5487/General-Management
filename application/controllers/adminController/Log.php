<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/5/30
 * Time: 下午4:34
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/adminController/Admin_Controller.php');

class Log extends Admin_Controller {

    private $_log_dir;
    private $_document;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('document');
        $this->_document = new Document();

        $aa = $this->_log_dir = 'application/logs/';
    }

    public function show_page()
    {
        $page = 'system_log';
        $title = 'system_log';

        $this->page_display($page, $title);
    }

    public function get_log_filels()
    {
        $log_dir = $this->_log_dir;
        $document = $this->_document;

        $log_files = $document->get_documents($log_dir, 1);

        if(empty($log_files['files']))
        {
            $this->ajax_return(CODE_NO_RESULT, "there's no log files");
        }

        $this->ajax_return(CODE_SUCCESS, 'success', $log_files['files']);
    }

    public function get_log_contents()
    {
        $file_name = $this->input->post('file_name');
        $log_path = $this->_log_dir . $file_name;

        if( ! is_file($log_path))
        {
            $this->ajax_return(CODE_ERROR, 'the file is not found');
        }

        $log_info = file_get_contents($log_path);
        $this->ajax_return(CODE_SUCCESS, 'success', $log_info);
    }

}