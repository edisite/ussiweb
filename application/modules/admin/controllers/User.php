<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	}

	// Frontend User CRUD
	public function index()
	{
		$crud = $this->generate_crud('users');
                $crud->set_theme('datatables');
		$crud->columns('username', 'first_name','address', 'active');
		$this->unset_crud_fields('ip_address', 'last_login');
                $crud->display_as('first_name', 'Name');

		// only webmaster and admin can change member groups
		if ($crud->getState()=='list' || $this->ion_auth->in_group(array('webmaster', 'admin')))
		{
			$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
		}

		// only webmaster and admin can reset user password
		if ($this->ion_auth->in_group(array('webmaster', 'admin','manager')))
		{
			$crud->add_action('Reset Password', '', 'admin/user/reset_password', 'fa fa-repeat');
		}

		// disable direct create / delete Frontend User
		$crud->unset_add();
		//$crud->unset_delete();

		$this->mTitle = 'Users Agent';
		$this->render_crud();
	}

	// Create Frontend User
	public function Create()
	{
            
                
            $form = $this->form_builder->create_form();
		if ($form->validate())
		{
			// passed validation
			$username           = $this->input->post('username');
			$email              = $this->input->post('email');
			$password           = $this->input->post('password');
			$identity           = empty($username) ? $email : $username;
			$additional_data    = array(
				'first_name'            => $this->input->post('first_name'),
				'address'		=> $this->input->post('addresss'),
				'nasabah_id'		=> $nasabahid,
				'pin_payment'		=> $this->input->post('pin'),
			);
			$groups = $this->input->post('groups') ?: 'member';

			// [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
			$this->ion_auth_model->tables = array(
				'users'				=> 'users',
				'groups'			=> 'groups',
				'users_groups'		=> 'users_groups',
				'login_attempts'	=> 'login_attempts',
			);

			// proceed to create user
			$user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups);	
                        var_dump($user_id);
                        echo "ok";
                        return;
			if ($user_id)
			{
				// success
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);

				// directly activate user
				$this->ion_auth->activate($user_id);
			}
			else
			{
				// failed
				$errors = $this->ion_auth->errors();
				$this->system_message->set_error($errors);
			}
			refresh();
		}
               
                $nasabahid = $this->input->get('nasabahid')     ?: '';
            
                if(empty($nasabahid)){
                    $this->messages->add('Nasabah sudah terdaftar di BMT USER', 'info');             
                    redirect('admin/user');
                }
                $this->load->model('user_model', 'users');
                $chek = $this->users->get_by('nasabah_id',$nasabahid);
                if($chek){
                    $this->messages->add('Nasabah sudah terdaftar di BMT USER', 'info');             
                    redirect('admin/user');
                }
                $getnas = $this->Nas_model->Nas_by_id($nasabahid);

                foreach ($getnas as $v) {
                    $namanasabah    = $v->nama_nasabah ?: '';
                    $alamat         = $v->alamat ?: '';                
                }
		// get list of Frontend user groups
		$this->load->model('group_model', 'groups');
		$this->mViewData['groups']      = $this->groups->get_all();
		$this->mViewData['nasabahid']   = $nasabahid;
		$this->mViewData['nama']        = $namanasabah;
		$this->mViewData['alamat']      = $alamat;
		$this->mTitle = 'Create User';

		$this->mViewData['form'] = $form;
		$this->render('user/create');
	}
        
        public function Usershow() {
        
            $crud = $this->generate_crud('nasabah');       
            $crud->set_model('Master_simpan_user_model');
            //$crud->set_theme('datatables');
            $crud->columns('NASABAH_ID','NO_REKENING','NAMA_NASABAH','ALAMAT','SALDO_AKHIR','TGL_REGISTER','VERIFIKASI');
            $crud->unset_read();
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->unset_export();
            $crud->unset_add();
            $crud->unset_print();
            $crud->add_action('Pilih', 'show_button', base_url().'admin/user/create/?nasabahid=');               
            $crud->set_subject('NASABAH');
            //$this->mTitle.= '[2000]Data master simpanan';
            $this->render_crud();     
        
        }
    
	// User Groups CRUD
	public function Group()
	{
		$crud = $this->generate_crud('groups');
		$this->mTitle = 'User Groups';
		$this->render_crud();
	}

	// Frontend User Reset Password
	public function Reset_password($user_id)
	{
		// only top-level users can reset user passwords
		$this->verify_auth(array('webmaster', 'admin'));

		$form = $this->form_builder->create_form();
		if ($form->validate())
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
		}

		$this->load->model('user_model', 'users');
		$target = $this->users->get($user_id);
                //var_dump($target);
		$this->mViewData['target'] = $target;

		$this->mViewData['form'] = $form;
		$this->mTitle = 'Reset User Password';
		$this->render('user/reset_password');
	}
}
