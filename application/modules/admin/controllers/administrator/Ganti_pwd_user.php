<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ganti_pwd_user
 *
 * @author edisite
 */
class Ganti_pwd_user extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->library('form_builder');
    }
    public function Reset_pass_user() {
                //$this->verify_auth(array('webmaster', 'admin'));

		$form = $this->form_builder->create_form();
		/*if ($form->validate())
		{
			// pass validation
			$data = array('password' => $this->input->post('new_password'));
			
			// [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
			$this->ion_auth_model->tables = array(
				'users'				=> 'users',
				'groups'			=> 'groups',
				'users_groups'		=> 'users_groups',
				'login_attempts'	=> 'login_attempts',
			);

			// proceed to change user password
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
		}*/

		$this->load->model('user_model', 'sys_daftar_user');
		$target = $this->sys_daftar_user->get('TELLER');
                print_r($target);/*
                //var_dump($target);
		$this->mViewData['target'] = $target;

		$this->mViewData['form'] = $form;
		$this->mTitle = 'Reset User Password';
		$this->render('administrator/ganti_pwd_user/reset_pass_user');
        
        */
    }
}
