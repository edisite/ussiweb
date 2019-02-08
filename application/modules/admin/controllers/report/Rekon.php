<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trans
 *
 * @author edisite
 */
class Rekon extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function All_transaksi() {
        $this->mMenuID                  = "8003";
        $this->mViewData['rekonsiliasi_tab']      = '';
        $this->mViewData['rekonsiliasi_dep']      = '';
        $this->mViewData['rekonsiliasi_kre']      = '';
        $this->mViewData['rekonsiliasi_com']      = '';
        $this->mViewData['tanggal_fr']      = '';
        $this->mViewData['tanggal_to']      = '';
        $this->form_validation->set_rules('tgl_fr','Tanggal Awal','required');
        $this->form_validation->set_rules('tgl_to','Tanggal Awal','required');
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_trans_rekonsiliasi');            
        }
        else{
            $tgl_fr     = $this->input->post('tgl_fr') ?: '';                     
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));            
            $tgl_to     = $this->input->post('tgl_to') ?: '';                              
            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            
            $data_tab = $this->Tab_model->Trx_per_month_sum($tgl_fr,$tgl_to);  
            $data_dep = $this->Dep_model->Trx_per_month_sum($tgl_fr,$tgl_to);  
            $data_kre = $this->Kre_model->Trx_per_month_sum($tgl_fr,$tgl_to);  
            $data_com = $this->Com_model->Trx_per_month_sum($tgl_fr,$tgl_to);  
            $this->mViewData['rekonsiliasi_tab']        = $data_tab;
            $this->mViewData['rekonsiliasi_dep']        = $data_dep;
            $this->mViewData['rekonsiliasi_kre']        = $data_kre;
            $this->mViewData['rekonsiliasi_com']        = $data_com;
            $this->mViewData['tanggal_fr']          = $tgl_fr;
            $this->mViewData['tanggal_to']          = $tgl_to;
            $this->render('report/lap_trans_rekonsiliasi');   
            
            
        }    
    }
}
