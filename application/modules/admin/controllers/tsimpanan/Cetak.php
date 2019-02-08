<?php
define('ipprinter', '192.168.1.195');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cetak
 *
 * @author edisite
 */
class Cetak extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Buku_tab($in_nomor_rekening) {
        if(empty($in_nomor_rekening)){
            redirect();
        }
//        $res_tgl = $this->Tab_model->Tabtrans_print_tgl($in_nomor_rekening);
//        foreach ($res_tgl as $val) {
//            $tgl_min = $val->min;
//        }
        $res_print = $this->Tab_model->Tabtrans_print($in_nomor_rekening);
        if(!$res_print){
            redirect();
        }
        
        $handle = printer_open("\\\\192.168.1.195\\epson");
        //if($handle){
            printer_start_doc($handle, "My Document");
            printer_start_page($handle);


            $font = printer_create_font("Arial", 20, 10, 200, false, false, false, 0);
            printer_select_font($handle, $font);
            $y=205;
            //for ($i=1; $i<=24; $i++){
            foreach ($res_print as $res) {                
            
//                    printer_draw_text($handle, echo $res->Tanggal,		5  , $y);
//                    printer_draw_text($handle, echo $res->Sandi,			140, $y);
//                    printer_draw_text($handle, echo number_format($res->Debet,2,",","."),				300, $y);
//                    printer_draw_text($handle, echo number_format($res->Kredit    ,2,",","."),	425, $y);
//                    printer_draw_text($handle, echo number_format($res->SALDO,2,",","."),	630, $y);
                    $y=$y+42.5;
            //};
            }
            printer_delete_font($font);
            printer_end_page($handle);
            printer_end_doc($handle);
            printer_close($handle);
            "Success";
//        }else{
//            "Couldn't connect..."; 
//            return;
//        }
        
    }
}
