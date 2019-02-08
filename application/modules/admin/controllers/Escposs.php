<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Escpos
 *
 * @author edisite
 */
class Escposs extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        //$this->load->library('Escpos');
    }
    public function Index() {
        try {
		// Enter the device file for your USB printer here
	  //$connector = new Escpos\PrintConnectors\FilePrintConnector("/dev/usb/lp0");
          $filename = "RCP_BMT";
          $connector = new Escpos\PrintConnectors\WindowsPrintConnector($filename);
          //var_dump($connector);
		/* Print a "Hello world" receipt" */
        $printer = new Escpos\Printer($connector);
                
        $printer->setEmphasis(true);
	$printer->text("FOO CORP Ltd.\n");
	$printer->setEmphasis(false);
	$printer->feed();
	$printer->text("Receipt for whatever\n");
	$printer->feed(4);

	/* Bar-code at the end */
	//$printer->setJustification(Printer::JUSTIFY_CENTER);
	$printer->barcode("987654321");
                //var_dump($printer);
		/* Close printer */
		$printer -> close();
        } catch (Exception $e) {
                echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
    public function Tes() {

        try {
                        // Enter the device file for your USB printer here
                        $connector = new Escpos\PrintConnectors\FilePrintConnector();
                        /* Print a "Hello world" receipt" */
                        $printer = new Escpos\Printer($connector);
                        $printer -> text("Hello World!\n");
                        $printer -> cut();

                        /* Close printer */
                        $printer -> close();
        } catch (Exception $e) {
                echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
        
    }
}
