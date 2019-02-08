    <?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Accounting
 *
 * @author edisite
 */
class Accounting extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('Trans');
    }
    public function Jurnal() {
        $trg_produk     = $this->Tab_model->produk();
        $trg_kdkantr    = $this->App_model->kode_kantor();
        
        $this->mViewData['kdprodk']     = $trg_produk;
        $this->mViewData['kdkantr']     = $trg_kdkantr;
        $this->mTitle                   = "[8351]Lap. Jurnal Transaksi";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal Awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');      

        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_ac_jurnal');            
        }
        else{
            $jenis_trx              = $this->input->post('jtrx');
            $kdkantor               = $this->input->post('kdkantor');  
            $tgl_fr                 = $this->input->post('tgl_fr');  
            $tgl_to                 = $this->input->post('tgl_to');  
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            //kdkantor=all&tgl_fr=01%2F11%2F2016&tgl_to=30%2F11%2F2016&jtrx=all
            
            $res_anggaran_basil     = $this->Trans->Jurnal($jenis_trx,$kdkantor,$tgl_fr,$tgl_to);           
            //var_dump($res_anggaran_basil);
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            $this->render('report/lap_ac_jurnal_res');
        }        
    }
    public function Lap_bb_buku_harian() {
        $this->load->model('admin_user2_model');
        $trg_produk                 = $this->Tab_model->produk();
        $trg_kdkantr                = $this->App_model->kode_kantor();
        $target                     = $this->admin_user2_model->usrjoin(); 
        $res_anggaran_basil         = $this->Perk_model->perk();
        $this->mViewData['mutasi']  = $res_anggaran_basil;
            
        $this->mViewData['kdprodk']     = $trg_produk;
        $this->mViewData['kdkantr']     = $trg_kdkantr;
        $this->mViewData['userjoin']    = $target;
        $this->mTitle                   = "[8351]Lap. Jurnal Transaksi";
        $this->mMenuID                   = "8354";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal Awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');      

        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_buku_besar_pembantu_buku_harian');            
        }
        else{
            $jenis_trx              = $this->input->post('jtrx');
            $kdkantor               = $this->input->post('kdkantor');  
            $tgl_fr                 = $this->input->post('tgl_fr');  
            $tgl_to                 = $this->input->post('tgl_to');  
            $tgl_fr                 = date('Y-m-d', strtotime($tgl_fr));
            $tgl_to                 = date('Y-m-d', strtotime($tgl_to));           
            $res_anggaran_basil     = $this->Trans->Jurnal($jenis_trx,$kdkantor,$tgl_fr,$tgl_to);           
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            $this->render('report/lap_buku_besar_pembantu_buku_harian_res');
        } 
    }
    public function Neraca_harian() {
        $data = $this->Trans->Neraca_temp();        
        $this->mTitle                   = "[8357]Lap. Neraca Harian";
        $this->mViewData['neraca']          = $data;
        $this->mMenuID                  = "8357";
        $this->render('report/lap_neraca_harian_res');
    }
    public function Neraca_harian_export_excel() {
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
        $this->excel->getActiveSheet()->setCellValue('A2', 'NERACA');
        $this->excel->getActiveSheet()->setCellValue('A3', 'PER 1 JANUARI 2017');

        $this->excel->getActiveSheet()->setCellValue('A4', 'Nama Perkiraan');
        $this->excel->getActiveSheet()->setCellValue('E4', 'Jumlah(Rp)');
        $this->excel->getActiveSheet()->setCellValue('G4', 'Nama Perkiraan');
        $this->excel->getActiveSheet()->setCellValue('K4', 'Jumlah(Rp)');


        $this->excel->getActiveSheet()->setCellValue('B6', 'AKTIVA');
        $this->excel->getActiveSheet()->setCellValue('H6', 'PASIVA');

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
        $this->excel->getActiveSheet()->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

        // Set column widths

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(2);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(6);

        // Set fonts

        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $this->excel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

        $this->excel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $this->excel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

        $this->excel->getActiveSheet()->getStyle('D13')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('E13')->getFont()->setBold(true);

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
        $this->excel->getActiveSheet()->getStyle('A4:L5')->applyFromArray($styleThickBrownBorderOutline);

        // Set fills

        $this->excel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle('A1:L1')->getFill()->getStartColor()->setARGB('FF808080');

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


        $this->excel->getActiveSheet()->insertNewRowBefore(6, 10);
        $this->excel->getActiveSheet()->removeRow(6, 10);
        $this->excel->getActiveSheet()->insertNewColumnBefore('E', 5);
        $this->excel->getActiveSheet()->removeColumn('E', 5);

        // Set header and footer. When no different headers for odd/even are used, odd header is assumed.

        $this->excel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BNeraca Harian&RPrinted on &D');
        $this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPage &P of &N');

        // Set page orientation and size

        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        // Rename first worksheet

        $this->excel->getActiveSheet()->setTitle('Neraca Harian');

      //  $result_sandi = $this->Tab_model->Sandi_trans(); 
        $this->excel->stream('neraca.xls', array());
    }
    
}
