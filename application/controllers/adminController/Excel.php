<?php

/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2018/1/31
 * Time: 上午10:25
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once (APPPATH . 'controllers/adminController/Admin_Controller.php');

class Excel extends Admin_Controller {
    public function __construct(){
        parent::__construct();
    }

    public function ExcelExport(){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $resultPHPExcel = new PHPExcel();
        //设置sheet标签
        $resultPHPExcel->setActiveSheetIndex(0);
        //设置sheet的name
        $resultPHPExcel->getActiveSheet()->setTitle('测试sheet');

        //合并单元格
        $resultPHPExcel->getActiveSheet()->mergeCells('A10:C15');

        //设置当前的sheet
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '项目');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '结果');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '数量');

        $i = 2;
        $m_data = ['A', 'B', 'C', 'D'];
        foreach($m_data as $key => $value){
            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value);
            $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value);
            $i ++;
        }

        $outputFileName = time().".xls";
        $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save( "php://output" );
    }
}