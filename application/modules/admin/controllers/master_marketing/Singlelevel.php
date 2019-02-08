<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Singlelevel
 *
 * @author edisite
 */
class Singlelevel extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('Mdownline');
    }
    public function Upline() {
        $crud = $this->generate_crud('com_downline'); 
        $crud->set_model('mdownline_model');
        $crud->set_theme('datatables');
        $crud->columns('parent_id','username');
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Upline');
        $this->mTitle .= '[1014] Transaksi Agen';
        $this->render_crud();
        
    }
    public function Upline_n($in_upline = '') {
                
                $data_upline    = $this->Mdownline->Master_upline();
                $GenUpline      = $this->Mdownline->Listupline();
                
                $crud = $this->generate_crud('com_downline'); 
                $crud->set_model('mdownline_model');
                $crud->set_theme('datatables');
                $crud->columns('username','first_name','last_name','dtm_ins');
                $crud->display_as('dtm_ins', 'Join');
                if(empty($in_upline)){  
                    $crud->where('parent_id', '0');
                }else{
                    $crud->where('parent_id', $in_upline);
                }
                $crud->unset_add();
                $crud->unset_export();
                $crud->unset_print();
                //$crud->unset_delete();
                $crud->unset_read();
                $crud->unset_edit();                
                $crud->set_subject('Upline');
                $this->mTitle .= 'Setting Upline Downline';
                $crud_obj_name = strtolower(get_class($this->mCrud));
		if ($crud_obj_name==='grocery_crud')
		{   
			$this->mCrud->unset_fields($this->mCrudUnsetFields);	
		}

		// render CRUD
		$crud_data = $this->mCrud->render();

 
		// append scripts
		$this->add_stylesheet($crud_data->css_files, FALSE);
		$this->add_script($crud_data->js_files, TRUE, 'head');

		// display view
		$this->mViewData['crud_output'] = $crud_data->output;
		$this->mViewData['crud_upline'] = $data_upline;
		$this->mViewData['GenUpline']   = $GenUpline;
		$this->mViewData['GenID']       = $in_upline;
                
                
		$this->render('downline/downline');
                
    }
    public function Upline_add($new_uid = '') {        
            if(empty($new_uid)){
                $this->messages->add('Parameter tidak lengkap','warning');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            }
            
            $cekstatus = $this->Mdownline->Upline_by_parentid($new_uid);
            if($cekstatus){
                $this->messages->add('Userid yang di pilih sudah terdaftar','info');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            }
            
            $arrinst = array(
                'parent_id' => $new_uid,
                'downline_id'   => $new_uid,
                'level' => '1',
                'position'  => '1',
                'dtm_ins'   => date('Y-m-d H:i:s'),
                'user_ins'  => $this->session->userdata('user_id')
            );
            
            $this->Tab_model->Ins_Tbl('com_downline',$arrinst);            
            $this->messages->add('Berhasil di tambahkan sebagai Upline','info');
            redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            
            
    }
    public function Downline_add($new_uid = '',$dl_id) {        
            if(empty($new_uid)){
                $this->messages->add('Parameter tidak lengkap','warning');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n/'.$new_uid);
            }
            if(empty($dl_id)){
                $this->messages->add('Parameter tidak lengkap','warning');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n/'.$new_uid);
            }
            
            $cekstatus = $this->Mdownline->Upline_by_parentid($dl_id);
            if($cekstatus){
                $this->messages->add('Userid yang di pilih sudah terdaftar','info');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n/'.$new_uid);
            }
            
            $arrinst = array(
                'parent_id' => $new_uid,
                'downline_id'   => $dl_id,
                'level' => '1',
                'position'  => '1',
                'dtm_ins'   => date('Y-m-d H:i:s'),
                'user_ins'  => $this->session->userdata('user_id')
            );
            
            $this->Tab_model->Ins_Tbl('com_downline',$arrinst);            
            $this->messages->add('Berhasil di tambahkan sebagai Downline','info');
            redirect(base_url().'admin/master_marketing/singlelevel/upline_n/'.$new_uid);
            
            
    }
    public function upline_remove($new_uid = '') {        
            if(empty($new_uid)){
                $this->messages->add('Parameter tidak lengkap','warning');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            }
            
            $cekstatus = $this->Mdownline->Upline_by_parentid($new_uid);
            if($cekstatus){
                $no = 1;
                foreach ($cekstatus as $v) {
                    $cuid = $v->parent_id;
                    $cudl = $v->downline_id;
                    
                    if($cudl == $new_uid){                        
                    }
                    else{
                        $no++;
                    }
                }
            }
            else{
                $this->messages->add('Userid yang di pilih tidak terdaftar sebagai upline','info');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            }
            
            if($no > 1){
                $this->messages->add('Tidak boleh di hapus, user upline masih memiliki downline.<br> Hapus dulu downline kemudian hapus ulang upline','warning');
                redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            }
            if($this->Mdownline->Delupline($new_uid))
            {
                $this->messages->add('Berhasil hapus data','info');
            }else{
                $this->messages->add('Hapus upline sudah berhasil','info');
            }
            redirect(base_url().'admin/master_marketing/singlelevel/upline_n');
            
            
    }
}
