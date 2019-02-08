<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tab
 *
 * @author edisite
 */
class Tab extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function LapPerKolektor() {
        $trg_integrasi  = $this->Kre_model->integrasi();
        $trg_kdkantr    = $this->App_model->kode_kantor();
        
        $this->mViewData['kdintgr']     = $trg_integrasi; 
        $this->mViewData['kdkantr']     = $trg_kdkantr; 
        
        
        //$this->mTitle                   = "[8057]Lap. Transaksi OB Basil, Pajak dan Adm";
        $this->mMenuID = "8085";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal','required');              
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_tab_per_kolektor');            
        }
        else{
            $tgl_fr             = $this->input->post('tgl_fr') ?: '';
            $tgl_to             = $this->input->post('tgl_to') ?: '';
            
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $tgl_to     = date('Y-m-d', strtotime($tgl_to));

            $result_tab     = $this->Tab_model->Perkolektor($tgl_fr,$tgl_to);
            if(!$result_tab){
                $this->messages->add('Data kosong','info');
            }
            $this->mViewData['result_tab']  = $result_tab;
            $this->mViewData['tgl_from']    = $tgl_fr;
            $this->mViewData['tgl_to']      = $tgl_to;
            $this->session->unset_userdata('laptab');
            $this->session->set_userdata('laptab',$result_tab);
            $this->session->unset_userdata('laptab_tgl_to');
            $this->session->set_userdata('laptab_tgl_to',date('d-m-Y', strtotime($tgl_to)));
            $this->session->unset_userdata('laptab_tgl_from');
            $this->session->set_userdata('laptab_tgl_from',date('d-m-Y', strtotime($tgl_fr)));
            $this->render('report/lap_tab_per_kolektor_res');   
        }
    }
    public function LapPerKolektor_export_excel() {
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

        $tgl_from       = $this->session->userdata('laptab_tgl_from') ?: '';
        $tgl_to         = $this->session->userdata('laptab_tgl_to') ?: '';

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setCellValue('A1', 'BMT EL SEJAHTERA');
        $this->excel->getActiveSheet()->setCellValue('A2', 'LAPORAN TABUNGAN PER KOLEKTOR');
        $this->excel->getActiveSheet()->setCellValue('A3', 'PER TANGGAL  '.$tgl_from.'   s/d   '.$tgl_to );

        $this->excel->getActiveSheet()->setCellValue('B4', 'ID');
        $this->excel->getActiveSheet()->setCellValue('C4', 'KOLEKTOR');
        $this->excel->getActiveSheet()->setCellValue('D4', 'PENARIKAN');
        $this->excel->getActiveSheet()->setCellValue('E4', 'TABUNGAN');
        $this->excel->getActiveSheet()->setCellValue('F4', 'STOCK OPNAME');
        
        $result = $this->session->userdata('laptab');
        
        $begin_col = "B";
        $begin_row = 6;
        $start_row = 6;
        foreach ($result as $v) { 
            
            
                $this->excel->getActiveSheet()->setCellValue('B'.$begin_row, $v->KODE_KOLEKTOR);
                $this->excel->getActiveSheet()->setCellValue('C'.$begin_row, $v->nama_kolektor);
                $this->excel->getActiveSheet()->setCellValue('D'.$begin_row, $v->penarikan_pokok);
                $this->excel->getActiveSheet()->setCellValue('E'.$begin_row, $v->tabungan_pokok);
                //$this->excel->getActiveSheet()->setCellValue('F'.$begin_row, $v->stok);
                $this->excel->getActiveSheet()->setCellValue('F'.$begin_row, '=D'.$begin_row.'-E'.$begin_row.')');

            $begin_row ++;
        }

        // Merge cells

        $this->excel->getActiveSheet()->mergeCells('A1:F1');
        $this->excel->getActiveSheet()->mergeCells('A2:F2');
        $this->excel->getActiveSheet()->mergeCells('A3:F3');

        $this->excel->getActiveSheet()->mergeCells('B4:B5');
        $this->excel->getActiveSheet()->mergeCells('C4:C5');
        $this->excel->getActiveSheet()->mergeCells('D4:D5');
        $this->excel->getActiveSheet()->mergeCells('E4:E5');
        $this->excel->getActiveSheet()->mergeCells('F4:F5');
        
        
        //$this->excel->getActiveSheet()->mergeCells('A28:B28');		// Just to test...
       // $this->excel->getActiveSheet()->unmergeCells('A28:B28');	// Just to test...

        // Set cell number formats
        $begin_cols_coma = "D";
        $end_cols_coma = "F";
        $this->excel->getActiveSheet()->getStyle($begin_cols_coma.$start_row.':'.$end_cols_coma.$begin_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        
        // set sum colomn
        $begin_row_sum = $begin_row + 1;
        $this->excel->getActiveSheet()->setCellValue('B'.$begin_row_sum, 'JUMLAH')
                              ->setCellValue('D'.$begin_row_sum, '=SUM(D'.$start_row.':D'.$begin_row.')')
                              ->setCellValue('E'.$begin_row_sum, '=SUM(E'.$start_row.':E'.$begin_row.')')
                              ->setCellValue('F'.$begin_row_sum, '=SUM(F'.$start_row.':F'.$begin_row.')');
        
        $this->excel->getActiveSheet()->mergeCells('B'.$begin_row_sum.':C'.$begin_row_sum);
        //
        $this->excel->getActiveSheet()->getStyle('B'.$begin_row_sum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //
        $this->excel->getActiveSheet()->getStyle($begin_cols_coma.$begin_row_sum.':'.$end_cols_coma.$begin_row_sum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->excel->getActiveSheet()->getStyle('B'.$begin_row_sum)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('D'.$begin_row_sum)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E'.$begin_row_sum)->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('F'.$begin_row_sum)->getFont()->setBold(true);
        // Set column widths

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        // Set fonts

        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

        

        // Set alignments

//        $this->excel->getActiveSheet()->getStyle('D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//        $this->excel->getActiveSheet()->getStyle('D12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//        $this->excel->getActiveSheet()->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//
//        $this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
//        $this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//
        $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
        $styleThickBrownBorderOutline1 = array(
                'borders' => array(
                        'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => 'FF993300'),
                        ),
                ),
        );
        $this->excel->getActiveSheet()->getStyle($begin_col.$start_row.':'.$end_cols_coma.$begin_row_sum)->applyFromArray($styleThickBrownBorderOutline1);
        // Set fills

        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FF808080');

        // Set style for header row using alternative method

        $this->excel->getActiveSheet()->getStyle('A1:F3')->applyFromArray(
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
        /*$this->excel->getActiveSheet()->getStyle($begin_col.$begin_col.':'.$begin_row_sum.$end_cols_coma)->applyFromArray(
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
        );*/


//        $this->excel->getActiveSheet()->insertNewRowBefore(6, 10);
//        $this->excel->getActiveSheet()->removeRow(6, 10);
//        $this->excel->getActiveSheet()->insertNewColumnBefore('E', 5);
        //$this->excel->getActiveSheet()->removeColumn('E', 5);

        // Set header and footer. When no different headers for odd/even are used, odd header is assumed.

        $this->excel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BLaporan Tabungan &RPrinted on &D');
        $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPage &P of &N');

        // Set page orientation and size

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        // Rename first worksheet

        $this->excel->getActiveSheet()->setTitle('Laporan Tabungan');

      //  $result_sandi = $this->Tab_model->Sandi_trans(); 
        $this->excel->stream('laporan tabungan.xls', array());
    }
    
}
