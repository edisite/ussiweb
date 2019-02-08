<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transaksi
 *
 * @author edisite
 */
class Transaksi extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('ag_transaksi_his_e');               
        //$crud->set_theme('datatables');
        //$crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Bank');
        $this->mTitle .= '[1014] Transaksi Agen';
        $this->render_crud();
    }
    
    public function Ses_log_trx_agent($tgl = '',$userid = '') {     
        if(empty($tgl) || empty($userid)){                    return FALSE;                }
        $arr_list = array('tab_ses', 'kre_ses','dep_ses','agent_ses','tab_ses_sum','deb_ses_sum');
        
        $this->session->unset_userdata($arr_list);
        $getdata_tab        = $this->Tab_model->Trx_per_day_by_agent($userid,$tgl);
        $this->session->set_userdata('tab_ses', $getdata_tab);
        $getdata_kre        = $this->Kre_model->Trx_per_day_by_agent($userid,$tgl);
        $this->session->set_userdata('kre_ses', $getdata_kre);
        $getdata_dep        = $this->Dep_model->Trx_per_day_by_agent($userid,$tgl);
        $this->session->set_userdata('dep_ses', $getdata_dep);
        
        $this->session->set_userdata('agent_ses', $userid);     
        
        $getdata_tab_sum    = $this->Tab_model->Trx_per_day_by_agent_sum($userid,$tgl);        
        $this->session->set_userdata('tab_ses_sum', $getdata_tab_sum);
        $getdata_kre_sum    = $this->Kre_model->Trx_per_day_by_agent_sum($userid,$tgl);
        $this->session->set_userdata('kre_ses_sum', $getdata_kre_sum);
        //$getdata_dep_sum    = $this->Dep_model->Trx_per_day_by_agent_sum($userid,$tgl);
        $this->session->set_userdata('dep_ses_sum', array());        
    }
    private function Ses_trx_peragent($userid = '',$tgl = '') {     
        if(empty($tgl) || empty($userid)){                    return FALSE;                }
        $arr_list = array('tab_peragent_ses', 'com_peragent_ses', 'kre_ses','dep_ses','agent_ses','tab_ses_sum','deb_ses_sum');
        
        $this->session->unset_userdata($arr_list);
        $getdata_tab        = $this->Tab_model->peragent($userid,$tgl);
        $this->session->set_userdata('tab_peragent_ses', $getdata_tab);
        $getdata_com        = $this->Com_model->peragent($userid,$tgl);
        $this->session->set_userdata('com_peragent_ses', $getdata_com);
        $getdata_kre        = $this->Kre_model->peragent($userid,$tgl);
        $this->session->set_userdata('kre_peragent_ses', $getdata_kre);       
    }
    public function Logagent() {              
        $this->load->model('Admin_user2_model');            
        //$username = $this->session->userdata('username');            
        //$this->load->library('form_validation');
        $this->form_validation->set_rules('tgl_fr','Tanggal','required');        
        $this->form_validation->set_rules('listagent','Agent','required');        

        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $in_src_agent = $this->session->userdata('agent_ses') ?: '';
           // $this->render('adm/agent');            
        }
        else{
            $in_src_dtime = $this->input->post('tgl_fr') ?: '';
            $in_src_agent = $this->input->post('listagent')?: '';
            $this->ses_log_trx_agent($in_src_dtime, $in_src_agent); 

        }    
        $get_userid_by_group = $this->Admin_user2_model->Agent_by_group_tabung($in_src_agent);        
        if($get_userid_by_group){
            foreach ($get_userid_by_group as $val) {
                $u_rekening     = $val->NO_REKENING;
                $u_nasabah_id   = $val->NASABAH_ID;
                $u_saldo        = $val->SALDO_AKHIR;
                $u_nama         = $val->nama;
                $u_userid       = $val->userid;
                $u_username     = $val->username;
                $u_status       = $val->STATUS;
            }                
        }else{
            $get_userid = $this->Admin_user2_model->User_by_username($in_src_agent);
            if(!$get_userid){
                $u_rekening     = "";
                $u_nasabah_id   = "";
                $u_saldo        = 0;
                $u_nama         = "";
                $u_userid       = "";
                $u_username     = "";
                $u_status       = "";
            }else{
                foreach ($get_userid as $val) {
                    $u_rekening     = '-';
                    $u_nasabah_id   = '-';
                    $u_saldo        = 0;
                    $u_nama         = $val->first_name.' '.$val->last_name;
                    $u_userid       = $val->id;
                    $u_username     = $val->username;
                    $u_status       = $val->active;
                }
                if($u_status == 1){
                    $u_status = 'ACTIVE';
                }else{
                    $u_status = 'INACTIVE';
                } 
            }
        }   
        $get_user_group = $this->Admin_user2_model->Usergroup();
        $this->mViewData['NO_REKENING'] = $u_rekening ?: '-';
        $this->mViewData['NAMA_NASABH'] = $u_nama ?: 'Name unavailable';
        $this->mViewData['SALDO_NSBAH'] = $this->Rp($u_saldo) ?: 0;
        $this->mViewData['USERNAME_NS'] = $u_username ?: 'Username unavailable';
        $this->mViewData['STATUS_NBAH'] = $u_status ?: '-';        
        $this->mViewData['USERNMGROUP'] = $get_user_group ?: '';   
        $this->mTitle = 'Log Agent';
        $this->mMenuID  = "1033";
        $this->render('adm/Logagent');        
    }
    public function Peragent() {               
        $this->load->model('Admin_user2_model');
        $this->mMenuID  = "1033";
        
        $this->form_validation->set_rules('tgl_fr','Tanggal','required');        
        $this->form_validation->set_rules('listagent','Agent','required');        
        $in_src_dtime   =   '';
        $in_src_agent   = '';
        if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            //$in_src_agent = $this->session->userdata('agent_ses') ?: '';              
        }
        else{
            $in_src_dtime = $this->input->post('tgl_fr') ?: '';
            $in_src_agent = $this->input->post('listagent')?: '';
            
            $in_src_dtime = date('Y-m-d', strtotime($in_src_dtime));
            $this->Ses_trx_peragent($in_src_agent,$in_src_dtime);                         

        } 
        $get_user_group = $this->Admin_user2_model->Usergroup();
        $this->mViewData['USERNMGROUP'] = $get_user_group ?: '';
        $this->mViewData['tgl_fr']    = $in_src_dtime ?: '-';
        $this->mViewData['agentid']    = $in_src_agent ?: '-';
        $this->render('report/lap_trx_per_agent');               
    }
    function Rp($value)
    {
        return number_format($value,2,",",".");
    }
    public function Kredit() {
        $this->mTitle = '[1025]Daftar Point';
        $this->render('user/angsuran');
    }
    
}
