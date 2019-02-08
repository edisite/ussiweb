<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Managemen_users
 *
 * @author edisite
 */
class Managemen_users extends Admin_Controller{
    //put your code here
      public function __construct() {
          
        parent::__construct();
        $this->load->library('form_builder');
        
    }
    public function index() {
        $crud = $this->generate_crud('sys_daftar_user');
        //$crud->set_theme('datatables');
        //$crud->set_theme('bootstrap');
                $crud->set_subject('USER');
		$crud->columns('USER_NAME', 'NAMA_LENGKAP', 'UNIT_KERJA', 'JABATAN');
                $crud->fields('USER_NAME', 'NAMA_LENGKAP', 'UNIT_KERJA', 'JABATAN');
                $crud->display_as('USER_NAME','USER NAME');
                $crud->display_as('NAMA_LENGKAP','NAMA LENGKAP');
                $crud->display_as('UNIT_KERJA','UNIT KERJA');
                $crud->display_as('JABATAN','JABATAN');
                //$crud->change_field_type('MENU_ID','invisible');
                $crud->change_field_type('USER_PASSWORD', 'INVISIBLE');
		// disable direct create / delete Admin User
                $crud->callback_add_field('NAMA_LENGKAP',array($this,'add_field_callback_1'));

		//$crud->unset_add();
		$crud->unset_delete();
//                /$crud->unset_edit();
                $crud->unset_export();
                //$crud->unset_view();
                $crud->unset_read();
                //$crud->unset_print();               
                
                $crud->add_action('Delete', 'show_button', base_url().'admin/administrator/managemen_users/delete/id/');
                $crud->add_action('Edit', 'show_button', base_url().'admin/administrator/managemen_users/edit/?id=');
                $crud->add_action('Koreksi', 'show_button', base_url().'admin/administrator/managemen_users/show/?_koreksi=&_sid=');
		
                $this->mTitle.= '[9000] Managemen User';
		$this->render_crud();
    }
    public function Edit($id = NULL) {

            // only top-level users can reset Admin User passwords
		$this->verify_auth(array('webmaster','admin','manager'));

		$form = $this->form_builder->create_form();
		if ($form->validate())
		{
			// pass validation
			$data = array('password' => $this->input->get('new_password'));
                        
			if ($this->ion_auth->update($user_id, $data))
			{
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);
			}
			else
			{
				$errors = $this->ion_auth->errors();
				$this->system_message->set_error($errors);
			}
			refresh();
		}
                //print_r($id);

		/*$this->load->model('sys_daftar_user_model','sys_daftar_user');
		$target = $this->sys_daftar_user->get_id($id);
		$this->mViewData['target'] = $target;

		$this->mViewData['form'] = $form;
		$this->mTitle.= 'Reset Admin User Password';
		$this->render(base_url().'admin/administrator/managemen_users/edit');*/
    }
    function add_field_callback_1()
    {
          return '<textarea name="address" class="texteditor"></textarea>';
    }
    public function Show($id = null) {
        $in_id = $this->input->get('_sid');
        
        if ( $this->ion_auth->in_group(array('webmaster', 'admin','manager')) )
        {
            if(!$this->session->userdata('username')){
                redirect(base_url());
            }
            
            $crud= $this->generate_crud('sys_user_menu_def');
            
                    
            
        }else{
            redirect(base_url());
        }
        
    }

   
}
