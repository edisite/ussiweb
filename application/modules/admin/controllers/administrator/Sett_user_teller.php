<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sett_user_teller
 *
 * @author edisite
 */
class Sett_user_teller extends Admin_Controller {
    //put your code here
    
    public function __construct() {
        parent::__construct();
        
        //$this->load->model('App_model');
       
    }
    public function index() {
        $crud = $this->generate_crud('admin_users');
        $crud->set_theme('datatables');
        $crud->set_model('Admin_user_join_model');
        $crud->columns('username','first_name','jabatan','UNIT_KERJA','KODE_PERK_KAS');
        if ( $this->ion_auth->in_group(array('webmaster','admin','manager')) )
        {
                $crud->add_action('Counter Sign', 'Update Counter Sign', 'admin/administrator/sett_user_teller/edit/countersign', '');
                $crud->add_action('Otorisator', 'Update Otorisator', 'admin/administrator/sett_user_teller/edit/otorisator', '');
                $crud->add_action('Teller', 'Update Teller', 'admin/administrator/sett_user_teller/edit/teller', '');
        }
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();
        //$crud->unset_view();
        $crud->set_subject('User Teller');
	$this->mTitle.= '[9017] SETTING USER TELLER';
        $this->render_crud();        
    }
    public function Edit ($scode,$user_id)
    {
            $this->load->model('admin_user_model', 'admin_users');
            $datakantor = $this->App_model->kode_kantor();
            $datauser   = $this->Sys_daftar_user_model->Byuser_id($user_id);
            if($datauser){                  
                foreach($datauser as $subdata){
                        $par_jabatan    = $subdata->JABATAN;
                        $par_terima     = $subdata->PENERIMAAN;
                        $par_keluar     = $subdata->PENGELUARAN;
                        $par_terimaob   = $subdata->PENERIMAAN_OB;
                        $par_keluarob   = $subdata->PENGELUARAN_OB;
                        $par_unit_kerja = $subdata->UNIT_KERJA;
                        $par_user_code  = $subdata->user_code;
                }
            }else{
                        $par_jabatan    = "";
                        $par_terima     = "";
                        $par_keluar     = "";
                        $par_terimaob   = "";
                        $par_keluarob   = "";
                        $par_unit_kerja = "";
                        $par_user_code  = "";
            }
            
            $target = $this->admin_users->get($user_id);
            $this->mViewData['target']              = $target;
            $this->mViewData['kdkantor']            = $datakantor;            
            //$this->mViewData['dtuser']      = $datauser;
    
            $this->mViewData['JABATAN']             = $par_jabatan;
            $this->mViewData['PENERIMAAN']          = $par_terima;
            $this->mViewData['PENGELUARAN']         = $par_keluar;
            $this->mViewData['PENERIMAAN_OB']       = $par_terimaob;
            $this->mViewData['PENGELUARAN_OB']      = $par_keluarob;
            $this->mViewData['UNIT_KERJA']          = $par_unit_kerja;
            $this->mViewData['USER_CODE']           = $par_user_code;
            $this->mTitle.= '[9017]Setting Teller';
            
            
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('tterima','Penerimaan(T)','numeric');        
            $this->form_validation->set_rules('tkeluar','Pengeluaran(T)','numeric');
            $this->form_validation->set_rules('tterimaob','Penerimaan(OB)','numeric');
            $this->form_validation->set_rules('tkeluarob','Penerimaan(OB)','numeric');
            $this->form_validation->set_rules('tunitkerja','Kode Kantor','required');
            $this->form_validation->set_rules('tjabatan','JABATAN','required');            
            
            if($this->form_validation->run()===FALSE){
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning" role="alert">', '</div>');
            $this->render('adm/sett_user_teller_count_oto');
            }else{
           
                    // pass validation
                
                    $in_jabatan         = $this->input->post('tjabatan');
                    $in_unitkerja       = $this->input->post('tunitkerja');
                    $in_terima          = $this->input->post('tterima');
                    $in_keluar          = $this->input->post('tkeluar');
                    $in_terimaob        = $this->input->post('tterimaob');
                    $in_keluarob        = $this->input->post('tkeluarob');
                    $in_usercode        = $this->input->post('tusercode');
                    
                    $set_message_error = "";
                    
                    
                    if($scode != $in_usercode){
                        if($scode == "1"){
                            $set_message_error = "Maaf tidak bisa di simpan, Setting user harus 'TELLER'.";
                        }else if($scode == "2"){
                            $set_message_error = "Maaf tidak bisa di simpan, Setting user harus 'OTORISATOR'.";                            
                        }else if($scode == "3"){
                            $set_message_error = "Maaf tidak bisa di simpan, Setting user harus 'COUNTER SIGN'.";
                        }else{
                            $set_message_error = "Maaf tidak bisa di simpan.";
                        }                        
                        
                        $this->mViewData['SETHEADLINE']             = "";
                        $this->mViewData['SETWARNTEXT']             = "Kesalahan";
                        $this->mViewData['SETINFOTEXT']             = $set_message_error;
                        $this->mViewData['SETCOLULANG']             = "administrator/Sett_user_teller/edit/".$scode."/".$user_id;
                        $this->mViewData['SETCOLHOME']             = "administrator/Sett_user_teller/";
                        
                        $this->render('errors/error_page');    
                        return;
                    }
            $result = $this->Sys_daftar_user_model->Upd_Userid($in_unitkerja,$in_terima,$in_keluar,$in_terimaob,$in_keluarob,$in_jabatan,$in_usercode,'putut');
               if($result){
                   redirect('admin/administrator/sett_user_teller/getlist');
               }else{                   
               }
                     
                    
            }
            
    }
    function Rp($value)
    {
        return number_format($value,2,",",".");
    }
}
