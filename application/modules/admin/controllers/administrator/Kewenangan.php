<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kewenangan
 *
 * @author edisite
 */
class Kewenangan extends Admin_Controller{
    //put your code here
     public function __construct() {
          
        parent::__construct();
        $this->load->library('form_builder');
        
    }   
    public function Getlist($id) {
       // only top-level users can reset Admin User passwords
		$this->verify_auth(array('webmaster'));

		$form = $this->form_builder->create_form();
		
		$this->load->model('admin_user_model', 'admin_users');
		$target = $this->admin_users->get($user_id);
		$this->mViewData['target'] = $target;

		$this->mViewData['form'] = $form;
		$this->mTitle.= 'Reset Admin User Password';
		$this->render('panel/admin_user_reset_password');
    }
}
