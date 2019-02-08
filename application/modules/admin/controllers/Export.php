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
class Export extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('Excel');
    }    
    public function index() {
        
        $this->excel->setActiveSheetIndex(0);             
        
       // Set document propertie
        $this->excel->getProperties()->setCreator("Edi Supriyanto")
                        ->setLastModifiedBy("Edi Supriyanto")
                        ->setTitle("elsejahtera")
                        ->setSubject("elsejahtera")
                        ->setDescription("elsejahtera.")
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
    public function Tesxl() {

        $this->excel->setActiveSheetIndex(0);        
        
        
       // Set document propertie
$this->excel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");


$this->excel->setActiveSheetIndex(0);
$this->excel->getActiveSheet()->setCellValue('B1', 'Invoice');
$this->excel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
$this->excel->getActiveSheet()->getStyle('D1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
$this->excel->getActiveSheet()->setCellValue('E1', '#12566');

$this->excel->getActiveSheet()->setCellValue('A3', 'Product Id');
$this->excel->getActiveSheet()->setCellValue('B3', 'Description');
$this->excel->getActiveSheet()->setCellValue('C3', 'Price');
$this->excel->getActiveSheet()->setCellValue('D3', 'Amount');
$this->excel->getActiveSheet()->setCellValue('E3', 'Total');

$this->excel->getActiveSheet()->setCellValue('A4', '1001');
$this->excel->getActiveSheet()->setCellValue('B4', 'PHP for dummies');
$this->excel->getActiveSheet()->setCellValue('C4', '20');
$this->excel->getActiveSheet()->setCellValue('D4', '1');
$this->excel->getActiveSheet()->setCellValue('E4', '=IF(D4<>"",C4*D4,"")');

$this->excel->getActiveSheet()->setCellValue('A5', '1012');
$this->excel->getActiveSheet()->setCellValue('B5', 'OpenXML for dummies');
$this->excel->getActiveSheet()->setCellValue('C5', '22');
$this->excel->getActiveSheet()->setCellValue('D5', '2');
$this->excel->getActiveSheet()->setCellValue('E5', '=IF(D5<>"",C5*D5,"")');

$this->excel->getActiveSheet()->setCellValue('E6', '=IF(D6<>"",C6*D6,"")');
$this->excel->getActiveSheet()->setCellValue('E7', '=IF(D7<>"",C7*D7,"")');
$this->excel->getActiveSheet()->setCellValue('E8', '=IF(D8<>"",C8*D8,"")');
$this->excel->getActiveSheet()->setCellValue('E9', '=IF(D9<>"",C9*D9,"")');

$this->excel->getActiveSheet()->setCellValue('D11', 'Total excl.:');
$this->excel->getActiveSheet()->setCellValue('E11', '=SUM(E4:E9)');

$this->excel->getActiveSheet()->setCellValue('D12', 'VAT:');
$this->excel->getActiveSheet()->setCellValue('E12', '=E11*0.21');

$this->excel->getActiveSheet()->setCellValue('D13', 'Total incl.:');
$this->excel->getActiveSheet()->setCellValue('E13', '=E11+E12');


$this->excel->getActiveSheet()->getComment('E11')->setAuthor('PHPExcel');
$objCommentRichText = $this->excel->getActiveSheet()->getComment('E11')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$this->excel->getActiveSheet()->getComment('E11')->getText()->createTextRun("\r\n");
$this->excel->getActiveSheet()->getComment('E11')->getText()->createTextRun('Total amount on the current invoice, excluding VAT.');

$this->excel->getActiveSheet()->getComment('E12')->setAuthor('PHPExcel');
$objCommentRichText = $this->excel->getActiveSheet()->getComment('E12')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$this->excel->getActiveSheet()->getComment('E12')->getText()->createTextRun("\r\n");
$this->excel->getActiveSheet()->getComment('E12')->getText()->createTextRun('Total amount of VAT on the current invoice.');

$this->excel->getActiveSheet()->getComment('E13')->setAuthor('PHPExcel');
$objCommentRichText = $this->excel->getActiveSheet()->getComment('E13')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$this->excel->getActiveSheet()->getComment('E13')->getText()->createTextRun("\r\n");
$this->excel->getActiveSheet()->getComment('E13')->getText()->createTextRun('Total amount on the current invoice, including VAT.');
$this->excel->getActiveSheet()->getComment('E13')->setWidth('100pt');
$this->excel->getActiveSheet()->getComment('E13')->setHeight('100pt');
$this->excel->getActiveSheet()->getComment('E13')->setMarginLeft('150pt');
$this->excel->getActiveSheet()->getComment('E13')->getFillColor()->setRGB('EEEEEE');


// Add rich-text string
$objRichText = new PHPExcel_RichText();
$objRichText->createText('This invoice is ');

$objPayable = $objRichText->createTextRun('payable within thirty days after the end of the month');
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );

$objRichText->createText(', unless specified otherwise on the invoice.');

$this->excel->getActiveSheet()->getCell('A18')->setValue($objRichText);

// Merge cells

$this->excel->getActiveSheet()->mergeCells('A18:E22');
$this->excel->getActiveSheet()->mergeCells('A28:B28');		// Just to test...
$this->excel->getActiveSheet()->unmergeCells('A28:B28');	// Just to test...

// Protect cells
$this->excel->getActiveSheet()->getProtection()->setSheet(true);	// Needs to be set to true in order to enable any worksheet protection!
$this->excel->getActiveSheet()->protectCells('A3:E13', 'PHPExcel');

// Set cell number formats
$this->excel->getActiveSheet()->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

// Set column widths

$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);

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

$this->excel->getActiveSheet()->getStyle('B5')->getAlignment()->setShrinkToFit(true);

// Set thin black border outline around column

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$this->excel->getActiveSheet()->getStyle('A4:E10')->applyFromArray($styleThinBlackBorderOutline);


// Set thick brown border outline around "Total"

$styleThickBrownBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THICK,
			'color' => array('argb' => 'FF993300'),
		),
	),
);
$this->excel->getActiveSheet()->getStyle('D13:E13')->applyFromArray($styleThickBrownBorderOutline);

// Set fills

$this->excel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$this->excel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FF808080');

// Set style for header row using alternative method

$this->excel->getActiveSheet()->getStyle('A3:E3')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
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

$this->excel->getActiveSheet()->getStyle('A3')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
			'borders' => array(
				'left'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);

$this->excel->getActiveSheet()->getStyle('B3')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		)
);

$this->excel->getActiveSheet()->getStyle('E3')->applyFromArray(
		array(
			'borders' => array(
				'right'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);

// Unprotect a cell

$this->excel->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

// Add a hyperlink to the sheet

$this->excel->getActiveSheet()->setCellValue('E26', 'www.phpexcel.net');
$this->excel->getActiveSheet()->getCell('E26')->getHyperlink()->setUrl('http://www.phpexcel.net');
$this->excel->getActiveSheet()->getCell('E26')->getHyperlink()->setTooltip('Navigate to website');
$this->excel->getActiveSheet()->getStyle('E26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$this->excel->getActiveSheet()->setCellValue('E27', 'Terms and conditions');
$this->excel->getActiveSheet()->getCell('E27')->getHyperlink()->setUrl("sheet://'Terms and conditions'!A1");
$this->excel->getActiveSheet()->getCell('E27')->getHyperlink()->setTooltip('Review terms and conditions');
$this->excel->getActiveSheet()->getStyle('E27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath(APPPATH . 'third_party/PHPExcel/Examples/images/officelogo.jpg');
$objDrawing->setHeight(36);
$objDrawing->setWorksheet($this->excel->getActiveSheet());

// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Paid');
$objDrawing->setDescription('Paid');
$objDrawing->setPath(APPPATH . 'third_party/PHPExcel/Examples/images/paid.png');
$objDrawing->setCoordinates('B15');
$objDrawing->setOffsetX(110);
$objDrawing->setRotation(25);
$objDrawing->getShadow()->setVisible(true);
$objDrawing->getShadow()->setDirection(45);
$objDrawing->setWorksheet($this->excel->getActiveSheet());

// Add a drawing to the worksheet

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath(APPPATH . 'third_party/PHPExcel/Examples/images/phpexcel_logo.gif');
$objDrawing->setHeight(36);
$objDrawing->setCoordinates('D24');
$objDrawing->setOffsetX(10);
$objDrawing->setWorksheet($this->excel->getActiveSheet());

// Play around with inserting and removing rows and columns

$this->excel->getActiveSheet()->insertNewRowBefore(6, 10);
$this->excel->getActiveSheet()->removeRow(6, 10);
$this->excel->getActiveSheet()->insertNewColumnBefore('E', 5);
$this->excel->getActiveSheet()->removeColumn('E', 5);

// Set header and footer. When no different headers for odd/even are used, odd header is assumed.

$this->excel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BInvoice&RPrinted on &D');
$this->excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->excel->getProperties()->getTitle() . '&RPage &P of &N');

// Set page orientation and size

$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Rename first worksheet

$this->excel->getActiveSheet()->setTitle('Invoice');


// Create a new worksheet, after the default sheet

$this->excel->createSheet();

// Llorem ipsum...
$sLloremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus eget ante. Sed cursus nunc semper tortor. Aliquam luctus purus non elit. Fusce vel elit commodo sapien dignissim dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur accumsan magna sed massa. Nullam bibendum quam ac ipsum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin augue. Praesent malesuada justo sed orci. Pellentesque lacus ligula, sodales quis, ultricies a, ultricies vitae, elit. Sed luctus consectetuer dolor. Vivamus vel sem ut nisi sodales accumsan. Nunc et felis. Suspendisse semper viverra odio. Morbi at odio. Integer a orci a purus venenatis molestie. Nam mattis. Praesent rhoncus, nisi vel mattis auctor, neque nisi faucibus sem, non dapibus elit pede ac nisl. Cras turpis.';

// Add some data to the second sheet, resembling some different data types

$this->excel->setActiveSheetIndex(1);
$this->excel->getActiveSheet()->setCellValue('A1', 'Terms and conditions');
$this->excel->getActiveSheet()->setCellValue('A3', $sLloremIpsum);
$this->excel->getActiveSheet()->setCellValue('A4', $sLloremIpsum);
$this->excel->getActiveSheet()->setCellValue('A5', $sLloremIpsum);
$this->excel->getActiveSheet()->setCellValue('A6', $sLloremIpsum);

// Set the worksheet tab color

$this->excel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;

// Set alignments

$this->excel->getActiveSheet()->getStyle('A3:A6')->getAlignment()->setWrapText(true);

// Set column widths

$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(80);

// Set fonts

$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

$this->excel->getActiveSheet()->getStyle('A3:A6')->getFont()->setSize(8);

// Add a drawing to the worksheet

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Terms and conditions');
$objDrawing->setDescription('Terms and conditions');
$objDrawing->setPath(APPPATH . 'third_party/PHPExcel/Examples/images/termsconditions.jpg');
$objDrawing->setCoordinates('B14');
$objDrawing->setWorksheet($this->excel->getActiveSheet());

// Set page orientation and size

$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Rename second worksheet

$this->excel->getActiveSheet()->setTitle('Terms and conditions');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$this->excel->setActiveSheetIndex(0);

      //  $result_sandi = $this->Tab_model->Sandi_trans(); 
        $this->excel->stream('name_of_file.xls', array());
    }

}
