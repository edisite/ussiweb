<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of trans_teller_new
 *
 * @author edisite
 */
class trans_teller_new extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('kode_perk');   
    }
    public function Preview() {
        $this->mViewData['tgl']         = "";        
        $this->mTitle                   = "[8402]Lap. Transaksi Teller";
        //$this->render('report/lap_trans_teller');     
    }
    public function Lap3() {
        
        $this->load->model('admin_user2_model');
        $target = $this->admin_user2_model->usrjoin();    
        $this->mViewData['tgl']         = "";
        $this->mViewData['userjoin']    = $target;        
        $this->mTitle                   = "[8403]Lap. Transaksi Teller";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usrid','User ID','required');        
        $this->form_validation->set_rules('paytype','Tipe Pembayaran','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_trans_teller3');
            
        }else{
            $in_usrnm   = $this->input->post('usrid');
            $in_pay     = $this->input->post('paytype');
            $in_dtm_fr  = $this->input->post('tgl_fr');
            $in_dtm_to  = $this->input->post('tgl_to');
            
            $this->load->model('Trans');
            $laporan     = $this->Trans->Tab_nas_tabtrans($in_pay,$in_usrnm,$in_dtm_fr,$in_dtm_to);
            //$data = array();
            //$data["laporan"]    = $laporan;
            $this->mViewData['data'] = $laporan;
            $this->render('report/dtables');
        }  
    }
    public function Showdata() {
        $in_usrnm   = $this->input->get('id');
        $in_pay     = $this->input->get('type');
        $in_dtm_fr  = $this->input->get('from');
        $in_dtm_to  = $this->input->get('to');
        
        $this->load->model('Trans');
        $respon     = $this->Trans->Tab_nas_tabtrans($in_pay,$in_usrnm,$in_dtm_fr,$in_dtm_to);
        $this->mViewData['laporan']    = $respon; 
        $this->render('report/lap_trans_teller3_pdf');
    }
    public function Cetak() { // tes datatables
        //$this->load->view('report/dtables');
        $this->render('report/dtables');
    }
}
