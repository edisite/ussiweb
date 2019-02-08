<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Export
 *
 * @author edisite
 */
class Acc extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('Excel');
    }    
    public function Neraca_harian() {
              
        //$this->mTitle                   = "[8357]Lap. Neraca Harian";
        $this->mMenuID                  = "8357";
        $this->mViewData['neraca']      = '';
        $this->mViewData['tanggal']      = '';
        $this->form_validation->set_rules('tgl_to','Tanggal Awal','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_neraca_harian_res');            
        }
        else{
            $tgl_to     = $this->input->post('tgl_to');            
            //$tgl_to     = '2017-04-21';            
            $tgl_to     = strtr($tgl_to, '/', '-');
            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $data = $this->Trans->Neraca_temp($tgl_to);  
            $this->mViewData['neraca']          = $data;
            $this->mViewData['tanggal']          = date('l, d F Y', strtotime($tgl_to));
            $this->session->unset_userdata('neracaharian_ses');
            $this->session->unset_userdata('neracaharian_ses_tgl');
            $this->session->set_userdata('neracaharian_ses',$data); 
            $this->session->set_userdata('neracaharian_ses_tgl',date('l, d F Y', strtotime($tgl_to))); 
            $this->render('report/lap_neraca_harian_res');
            
            
        }    
        
    }
    public function Neraca_harian_export_excel() {
        $this->load->library('Excel');
        $this->excel->setActiveSheetIndex(0);             
        
       // Set document propertie
        $this->excel->getProperties()->setCreator("Edi Supriyanto")
                        ->setLastModifiedBy("Edi Supriyanto")
                        ->setTitle("elsejahtera")
                        ->setSubject("elsejahtera")
                        ->setDescription("elsejahtera")
                        ->setKeywords("report")
                        ->setCategory("report");


        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setCellValue('A1', 'BMT EL SEJAHTERA');
        $this->excel->getActiveSheet()->setCellValue('A2', 'NERACA HARIAN');
        $this->excel->getActiveSheet()->setCellValue('A3', 'PER 1 JANUARI 2017');

        $this->excel->getActiveSheet()->setCellValue('A4', 'Nama Perkiraan');
        $this->excel->getActiveSheet()->setCellValue('E4', 'Jumlah(Rp)');
        $this->excel->getActiveSheet()->setCellValue('G4', 'Nama Perkiraan');
        $this->excel->getActiveSheet()->setCellValue('K4', 'Jumlah(Rp)');


        $this->excel->getActiveSheet()->setCellValue('B6', 'AKTIVA');
        $this->excel->getActiveSheet()->setCellValue('H6', 'PASIVA');
        
        $result = $this->session->userdata('neracaharian_ses');
        //var_dump($result);
        $begin_col = "B";
        $begin_row = 6;
        $start_row = 6;
        if($result){
            
            foreach ($result as $v) {   
                
                if($v->group_neraca == "AKTIVA"){
                    if(strlen($v->kode_perk) <= 3){
                        $this->excel->getActiveSheet()->setCellValue('B'.$begin_row, $v->nama_perk);
                        $this->excel->getActiveSheet()->setCellValue('E'.$begin_row, $v->saldo_akhir);
                        $this->excel->getActiveSheet()->getStyle('E'.$begin_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $this->excel->getActiveSheet()->getStyle('B'.$begin_row)->getFont()->setBold(true);    
                        $this->excel->getActiveSheet()->getStyle('E'.$begin_row)->getFont()->setBold(true);    
                    }else{
                    $this->excel->getActiveSheet()->setCellValue('C'.$begin_row,  str_replace('Simpanan', '',$v->nama_perk));
                    $this->excel->getActiveSheet()->setCellValue('D'.$begin_row, $v->saldo_akhir);
                    $this->excel->getActiveSheet()->getStyle('D'.$begin_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }
                    $begin_row ++;
                    $this->excel->getActiveSheet()->getStyle('B'.$begin_row.':E'.$begin_row)->getFont()->setSize(10);                    
                }
            }
            $begin_row = 6;
            foreach ($result as $v) {        
                if($v->group_neraca == "PASIVA"){
                    if(strlen($v->kode_perk) <= 3){
                        if($v->g_or_d == "G"){
                        $this->excel->getActiveSheet()->setCellValue('H'.$begin_row, $v->nama_perk);
                        $this->excel->getActiveSheet()->setCellValue('K'.$begin_row, $v->saldo_akhir);
                        $this->excel->getActiveSheet()->getStyle('K'.$begin_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $this->excel->getActiveSheet()->getStyle('H'.$begin_row)->getFont()->setBold(true);    
                        $this->excel->getActiveSheet()->getStyle('K'.$begin_row)->getFont()->setBold(true);    
                        $begin_row ++;
                        }else{
                            
                        }                        
                    }else{
                    $this->excel->getActiveSheet()->setCellValue('I'.$begin_row, $v->nama_perk);
                    $this->excel->getActiveSheet()->setCellValue('J'.$begin_row, $v->saldo_akhir);
                    $this->excel->getActiveSheet()->getStyle('J'.$begin_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $begin_row ++;                    
                    }
                    $this->excel->getActiveSheet()->getStyle('H'.$begin_row.':K'.$begin_row)->getFont()->setSize(10);
                    
                
                }
            }
        }
        // Merge cells

        $this->excel->getActiveSheet()->mergeCells('A1:L1');
        $this->excel->getActiveSheet()->mergeCells('A2:L2');
        $this->excel->getActiveSheet()->mergeCells('A3:L3');
        $this->excel->getActiveSheet()->mergeCells('A4:D5');
        $this->excel->getActiveSheet()->mergeCells('E4:F5');
        $this->excel->getActiveSheet()->mergeCells('G4:J5');
        $this->excel->getActiveSheet()->mergeCells('K4:L5');

        $this->excel->getActiveSheet()->mergeCells('A28:B28');		// Just to test...
        $this->excel->getActiveSheet()->unmergeCells('A28:B28');	// Just to test...

        // Set cell number formats
        //$this->excel->getActiveSheet()->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

        // Set column widths

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(2);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);

        // Set fonts

        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

        
//        $this->excel->getActiveSheet()->getStyle('D13')->getFont()->setBold(true);
//        $this->excel->getActiveSheet()->getStyle('E13')->getFont()->setBold(true);

        // Set alignments

        $this->excel->getActiveSheet()->getStyle('D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->excel->getActiveSheet()->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        $this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // Set thin black border outline around column

        // Set thick brown border outline around "Total"
        $styleThickBrownBorderOutline = array(
                'borders' => array(
                        'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => 'FF993300'),
                        ),
                ),
        );
        $this->excel->getActiveSheet()->getStyle('A4:F5')->applyFromArray($styleThickBrownBorderOutline);
        $this->excel->getActiveSheet()->getStyle('G4:L5')->applyFromArray($styleThickBrownBorderOutline);
        $this->excel->getActiveSheet()->getStyle('A6:F60')->applyFromArray($styleThickBrownBorderOutline);
        $this->excel->getActiveSheet()->getStyle('G6:L60')->applyFromArray($styleThickBrownBorderOutline);

        // Set fills

        $this->excel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        //$this->excel->getActiveSheet()->getStyle('A1:L1')->getFill()->getStartColor()->setARGB('FF808080');

        // Set style for header row using alternative method

        $this->excel->getActiveSheet()->getStyle('A1:L3')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		)
        );

        // Set header and footer. When no different headers for odd/even are used, odd header is assumed.

        $this->excel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BNeraca Harian&RPrinted on &D');
        $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPage &P of &N');

        // Set page orientation and size

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        // Rename first worksheet

        $this->excel->getActiveSheet()->setTitle('Neraca Harian');

      //  $result_sandi = $this->Tab_model->Sandi_trans(); 
        $this->excel->stream('neraca harian.xls', array());
        //$this->excel->stream('laporan_tabungan.xls', array());
    }
}
