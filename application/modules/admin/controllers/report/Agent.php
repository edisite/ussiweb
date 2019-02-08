<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dep
 *
 * @author edisite
 */
class Agent extends Admin_Controller{
    //put your code 
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_user2_model');
    }
    public function Dep_per_agent() {
        $trg_user       = $this->Admin_user2_model->Agent_group();
        $this->mViewData['usragent']     = $trg_user; 
        
        $this->mTitle                   = "Lap. Transaksi Per-Agent";
        $this->mMenuID                   = "1031";
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');        
        $this->form_validation->set_rules('userid','User ID','required');        
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_dep_per_agent');            
        }
        else{
            $tgl_fr             = $this->input->post('tgl_fr') ?: '';
            $tgl_to             = $this->input->post('tgl_to');
            $userid             = $this->input->post('userid') ?: 'all';

            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_history = $this->Dep_model->Trx_agent_history($userid,$tgl_fr,$tgl_to);            

            $this->mViewData['history']      = $res_history;
            $this->render('report/lap_dep_per_agent_res');
        }
    }
    public function Kre_per_agent() {
        $trg_user       = $this->Admin_user2_model->Agent_group();
        $this->mViewData['usragent']     = $trg_user; 
        
        $this->mTitle                   = "Lap. Transaksi Pembiayaan Per-Agent";
        $this->mMenuID                   = "1031";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');        
        $this->form_validation->set_rules('userid','User ID','required');        
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_kre_per_agent');            
        }
        else{
            $tgl_fr             = $this->input->post('tgl_fr') ?: '';
            $tgl_to             = $this->input->post('tgl_to');
            $userid             = $this->input->post('userid') ?: 'all';

            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_history = $this->Dep_model->Trx_agent_history($userid,$tgl_fr,$tgl_to);            

            $this->mViewData['history']      = $res_history;
            $this->render('report/lap_kre_per_agent_res');
        }
    }
    public function Tab_per_agent() {
        $trg_user       = $this->Admin_user2_model->Agent_group();
        $this->mViewData['usragent']     = $trg_user; 
        
        $this->mTitle                    = "Lap. Transaksi Tabungan Per-Agent";
        $this->mMenuID                   = "1030";
        $this->mViewData['history']      = '';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal awal','required');        
        $this->form_validation->set_rules('tgl_to','Tanggal Akhir','required');        
        $this->form_validation->set_rules('userid','User ID','required');        
        
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('report/lap_tab_per_agent');            
        }
        else{
            $tgl_fr             = $this->input->post('tgl_fr') ?: '';
            $tgl_to             = $this->input->post('tgl_to');
            $userid             = $this->input->post('userid') ?: 'all';

            $tgl_to     = date('Y-m-d', strtotime($tgl_to));
            $tgl_fr     = date('Y-m-d', strtotime($tgl_fr));
            $res_history = $this->Tab_model->Trx_agent_history($userid,$tgl_fr,$tgl_to);            
            
            $this->mViewData['history']      = $res_history;
            $this->render('report/lap_tab_per_agent');
        }
    }
}
