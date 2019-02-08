<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author edisite
 */
class Login extends MY_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();     
        $this->load->library('session');        
    }
    public function Index()
    {
        $this->load->view('login');        
    }
    public function Ra() {
        $this->load->view('registrasiaktivasi');
    }
    public function Verify() {
        $this->session->unset_userdata('infologin');
        $identity   = $this->input->post('username') ?: '';
        $password   = $this->input->post('password') ?: '';
        $remember   = ($this->input->post('remember')=='on');
        
        if(empty($identity) || empty($password)){            
            $this->session->set_userdata('infologin','Username & password terdeteksi kosong, cek kembali');
            redirect(base_url().'agen/login');
        }
        $this->load->model('Api_ion_auth');
        $resdata = $this->Api_ion_auth->login_api($identity,$password,$remember);
        if($resdata){  
            
        }  else {
            $this->session->set_userdata('infologin','Username dan password salah');
            redirect(base_url().'agen/login');
        }
        
        $res_admin  = $this->Admin_user2_model->User_by_username($identity);
        if($res_admin){
            foreach ($res_admin as $val) {
                $ou_agentid     = $val->id;
                $ou_username    = $val->username;
                $ou_first       = $val->first_name;
                $ou_last        = $val->last_name;
                $ou_status      = $val->active;
            }
        }else{
            $this->session->set_userdata('infologin','Username dan password salah');
            redirect('agen/login');
        }         
        
        if(trim($ou_status) == 0){
            $this->session->set_userdata('infologin','Username tidak aktif');
            redirect(base_url().'agen/login');
        }
        $this->session->set_userdata('ident',$ou_agentid);
        $this->session->set_userdata('agn_username',$ou_username);
        $this->session->set_userdata('agn_agentid',$ou_agentid);
        $this->session->set_userdata('agn_nameagen',$ou_first.' '.$ou_last);
        redirect('agen/report/trx_perday');
    }
    protected function Letscheck($username) {
        if($this->session->userdata('agn_username')){
            if($this->session->userdata() == $username){
                redirect(base_url().'agen/report/trx_perday');
            }
            $this->session->set_userdata('infologin','Session habis, silakan login kembali');
            redirect(base_url().'agen/login');
        }
    }
    function logged_in()
    {
            return (bool) $this->session->userdata('ident');
    }
    public function logout()
	{
		// Destroy the session
                $this->session->unset_userdata('ident');
                $this->session->unset_userdata('agn_username');
                $this->session->unset_userdata('agn_agentid');
                $this->session->unset_userdata('agn_nameagen');                
                $this->session->sess_destroy();
                redirect(base_url().'agen/login');
	}
}
