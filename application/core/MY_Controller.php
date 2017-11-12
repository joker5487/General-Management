<?php
/**
 * Created by PhpStorm.
 * User: joker
 * Date: 2017/11/12
 * Time: 15:06
 */

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library("Ci_smarty");
    }

    public function assign($key, $val) {
        $this->ci_smarty->assign($key, $val);
    }

    public function display($html) {
        $this->ci_smarty->display($html);
    }

}