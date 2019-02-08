<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kredit
 *
 * @author edisite
 */
class Kredit extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function LapPerKolektor() {
        $this->mTitle                   = "[8057]Lap. Transaksi OB Basil, Pajak dan Adm";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal','required');              
        $this->form_validation->set_rules('tgl_to','Tanggal','required');              
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_tab_per_kolektor');          
        }
        else{
            $tgl_to        = $this->input->post('tgl_to');
            $tgl_fr        = $this->input->post('tgl_fr');
            
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $res_anggaran_basil     = $this->Kre_model->Trx_per_day_by_agent_sum($tgl_fr,$tgl_to);
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            $this->render('report/lap_kredit_per_kolektif');   
        }
    }
}
