<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 include_once APPPATH.'/third_party/mpdf1/mpdf.php';
 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_pdf
 *
 * @author edisite
 */

class M_pdf {
    //put your code here
    public $param;
    public $pdf;
 
    public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    {
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
    }
}