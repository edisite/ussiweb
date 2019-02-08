<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of List_point
 *
 * @author edisite
 */
class Point extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $this->mTitle = '[1025]Daftar Point';
        $this->render('pembelian/pln_token');
    }
    public function Addnew() {
        $this->mTitle = '[1025]Daftar Point';
        $this->render('pembelian/pln_token');
    }
    public function Kredit() {
        $this->mTitle = '[1025]Daftar Point';
        $this->render('user/angsuran');
    }
    public function Deposito() {
        $this->mTitle = '[1025]Daftar Point';
        $this->render('user/deposito');
    }
    public function simpanan() {
        $this->mTitle = '[1025]Daftar Point';
        $this->render('user/tabungan');
    }
    public function Trans()
	{
                $res_sys_jurnal = $this->Css_model->Kodejurnal();
                $res_tab_kd_trans = $this->Tab_model->Kode_trans_trx_agent();
                $res_kre_kd_trans = $this->Kre_model->Kode_trans_trx_agent();
                $res_dep_kd_trans = $this->Dep_model->Kode_trans_trx_agent();
                
                $data = array();
                $data_jun = array();
                if($res_tab_kd_trans){
                    foreach ($res_tab_kd_trans as $val) {
                        $kd_trans = $val->KODE_TRANS ?: '';
                        $kd_descr = $val->DESKRIPSI_TRANS ?: '';
                        $data[$kd_trans] =  'TAB ['.$kd_trans.'] '.$kd_descr; 
                    }
                }
                if($res_kre_kd_trans){
                    foreach ($res_kre_kd_trans as $val) {
                        $kd_trans = $val->KODE_TRANS ?: '';
                        $kd_descr = $val->DESKRIPSI_TRANS ?: '';
                        $data[$kd_trans] =  'KRE ['.$kd_trans.'] '.$kd_descr; 
                    }
                }
                if($res_dep_kd_trans){
                    foreach ($res_dep_kd_trans as $val) {
                        $kd_trans = $val->KODE_TRANS ?: '';
                        $kd_descr = $val->DESKRIPSI_TRANS ?: '';
                        $data[$kd_trans] =  'DEP ['.$kd_trans.'] '.$kd_descr; 
                    }
                }
                if($res_sys_jurnal){
                    foreach ($res_sys_jurnal as $val) {
                        $kd_trans = $val->KODE_JURNAL ?: '';
                        $kd_descr = $val->DESKRIPSI_JURNAL ?: '';
                        $data_jun[$kd_trans] = $kd_trans; 
                    }
                }else{
                    $data_jun[] = '';
                }
               
		$crud = $this->generate_crud('tbl_transaksi');
                $crud->set_theme('datatables');
                $crud->required_fields('no_transaksi,jenis_transaksi,kode_transaksi,status_poin,poin,kredit'); 
                //$crud->edit_fields('status_poin','poin','kredit');
                $crud->callback_add_field('no_transaksi',function () {
                    return '<input type="text" maxlength="50" value="'.$this->App_model->Gen_id().'" name="no_transaksi" class="readonly">';
                });
                $crud->field_type('status_poin','enum',array('Value','Equal'));
                $crud->field_type('jenis_transaksi','dropdown',$data_jun);
               // $crud->field_type('kode_transaksi','dropdown',$data);
                $crud->callback_edit_field('kode_transaksi',array($this,'callback_edit_kode'));
                $crud->callback_add_field('kode_transaksi',array($this,'callback_edit_kode'));
 
		// cannot change Admin User groups once created
		// disable direct create / delete Admin User
		$crud->unset_export();
                $crud->unset_print();   
                //$crud->unset_add();
		$crud->unset_delete();
		//$crud->unset_edit();                
		$this->mTitle.= 'Transaksi Poin Agent';
		$this->render_crud();
	}
        function callback_edit_kode($decrypted_password ='9232892479') {            
               $res_tab_kd_trans = $this->Tab_model->Kode_trans_trx_agent();
                $res_kre_kd_trans = $this->Kre_model->Kode_trans_trx_agent();
                $res_dep_kd_trans = $this->Dep_model->Kode_trans_trx_agent();
            $dropdw = '<select id="kode_transaksi" name="kode_transaksi">
                    ';     
                        $res_tab_kd_trans = $this->Tab_model->Kode_trans_trx_agent();
                        $res_kre_kd_trans = $this->Kre_model->Kode_trans_trx_agent();
                        $res_dep_kd_trans = $this->Dep_model->Kode_trans_trx_agent();
                        
                        if($res_tab_kd_trans){
                        foreach ($res_tab_kd_trans as $val) {
                            $kd_trans = $val->KODE_TRANS ?: '';
                            $kd_descr = $val->DESKRIPSI_TRANS ?: '';
                            $data =  'TAB ['.$kd_trans.'] '.$kd_descr; 
                            $dropdw .= '<option value="'.$kd_trans.'">'.$data.'</option>';
                            }
                        }
                        if($res_kre_kd_trans){
                            foreach ($res_kre_kd_trans as $val) {
                                $kd_trans = $val->KODE_TRANS ?: '';
                                $kd_descr = $val->DESKRIPSI_TRANS ?: '';
                                $data =  'KRE ['.$kd_trans.'] '.$kd_descr; 
                                $dropdw .= '<option value="'.$kd_trans.'">'.$data.'</option>';
                            }
                        }
                        if($res_dep_kd_trans){
                            foreach ($res_dep_kd_trans as $val) {
                                $kd_trans = $val->KODE_TRANS ?: '';
                                $kd_descr = $val->DESKRIPSI_TRANS ?: '';
                                $data =  'DEP ['.$kd_trans.'] '.$kd_descr;
                                $dropdw .= '<option value="'.$kd_trans.'">'.$data.'</option>';
                            }
                        }
                    
                    $dropdw .= '</select>';
                    return $dropdw;
        }
    
    public function Hist()
	{
		$crud = $this->generate_crud('tbl_history_r');
                //$crud->set_theme('datatables');
		//$crud->columns('groups', 'username', 'first_name', 'last_name', 'active');
		//$crud->fields('username', 'first_name', 'last_name', 'active','penerimaan','pengeluaran');
		//$this->unset_crud_fields('ip_address', 'last_login');
                //$crud->field_type('status_poin','enum',array('Value','Equal'));
		// cannot change Admin User groups once created
		// disable direct create / delete Admin User
		$crud->unset_export();
                $crud->unset_print();   
                $crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();

		$this->mTitle.= 'History Poin Agent';
                $this->mMenuID  = '1020';
		$this->render_crud();
	}
	// Create Admin User
	public function Trans_add()
	{
		// (optional) only top-level admin user groups can create Admin User
		//$this->verify_auth(array('webmaster'));

		$form = $this->form_builder->create_form();

		if ($form->validate())
		{
			// passed validation
			$username   = $this->input->post('username');
			$email      = $this->input->post('email');
			$password   = $this->input->post('password');
			$additional_data = array(
				'first_name'	=> $this->input->post('first_name'),
				'last_name'		=> $this->input->post('last_name'),
			);
			$groups = $this->input->post('groups');

			// create user (default group as "members")
			$user = $this->ion_auth->register($username, $password, $email, $additional_data, $groups);
			if ($user)
			{
				// success
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);
			}
			else
			{
				// failed
				$errors = $this->ion_auth->errors();
				$this->system_message->set_error($errors);
			}
			refresh();
		}

		$groups = $this->ion_auth->groups()->result();
		unset($groups[0]);	// disable creation of "webmaster" account
		$this->mViewData['groups'] = $groups;
		$this->mTitle.= 'Create Admin User';

		$this->mViewData['form'] = $form;
		$this->render('panel/admin_user_create');
	}
        public function Poin() {
             $crud = $this->generate_crud('tbl_history_r');   
            //$crud->set_theme('datatables');
            $crud->set_model('Agent_poin_model');
            
           
            //$crud->add_action('HAPUS', 'show_button', base_url().'admin/bo_deposito/browse_simjaka/deptrx_hps/?transid=');
            //$crud->add_action('PEMBATALAN', 'show_button', '');

            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_export();
            $crud->unset_print();
            $crud->set_subject('Trx');

            $this->mTitle.= '[3005]Browse Daftar Transaksi Simpanan Berjangka';
            $this->render_crud();
            
        }
}
