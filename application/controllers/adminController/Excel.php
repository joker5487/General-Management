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

        $objPHPExcel = new PHPExcel();
        // 设置sheet标签
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置sheet的name
        $objPHPExcel->getActiveSheet()->setTitle('测试sheet');

        // 合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        // 设置行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        // 自动行高
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // 设置加粗
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

        //设置当前的sheet
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '导出测试excel样式及内容');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'IMG');
        $objPHPExcel->getActiveSheet()->setCellValue('B2', '项目');
        $objPHPExcel->getActiveSheet()->setCellValue('C2', '结果');
        $objPHPExcel->getActiveSheet()->setCellValue('D2', '数量');
        $objPHPExcel->getActiveSheet()->setCellValue('E2', '统计');

        $i = 3;
        $m_data = ['12', '5445', '457', '63'];
        foreach($m_data as $key => $value){
            // 设置行高
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(45);
            $objPHPExcel->getActiveSheet()->getDefaultColumnDimension('A'.$i)->setWidth(8);

            // 图片生成
            $imgPath = PUBLIC_IMG_RESOURCE_PATH . 'default-user.jpg';
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath($imgPath);
            // 设置宽度高度
            $objDrawing->setHeight(40);//照片高度
            $objDrawing->setWidth(40); //照片宽度
            /*设置图片要插入的单元格*/
            $objDrawing->setCoordinates('A'.$i);
            // 图片偏移距离
            $objDrawing->setOffsetX(10);
            $objDrawing->setOffsetY(10);
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, '=SUM(B'.$i.':D'.$i.')');
            $i ++;
        }

        $outputFileName = time().".xls";
        $xlsWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
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