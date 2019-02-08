<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {
    protected $uexistname;
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
		$crud->columns('username', 'email', 'first_name', 'last_name', 'id_nasabah','active');
		$this->unset_crud_fields('ip_address', 'last_login');

		// only webmaster and admin can change member groups
		if ($crud->getState()=='list' || $this->ion_auth->in_group(array('webmaster', 'admin')))
		{
			$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
		}

		// only webmaster and admin can reset user password
		if ($this->ion_auth->in_group(array('webmaster', 'admin','manager')))
		{   
			$crud->add_action('Reset Password', '', 'admin/agent/user/reset_password', 'fa fa-repeat');
			$crud->add_action('Config', '', 'admin/agent/user/config', 'fa fa-repeat');
		}

		// disable direct create / delete Frontend User
		$crud->unset_add();
		$crud->unset_delete();
                $crud->unset_print();
                $crud->unset_export();  

		$this->mTitle = '[1018] User Agent';
		$this->render_crud();
	}

	// Create Frontend User
	public function create($in_userid = null)
	{
		if(empty($in_userid)){
                    redirect('admin/panel/admin_user/');
                }
                $crud = $this->generate_crud('nasabah');
                $crud->set_model('Tabung_nasabah_join_model');
		$crud->columns('NASABAH_ID','NO_REKENING' ,'NAMA_NASABAH','JENIS_KELAMIN', 'ALAMAT');
                $crud->callback_add_field('USER_PASSWORD',array($this,'add_field_callback_1'));
                $crud->callback_edit_field('phone',array($this,'edit_field_callback_1'));
                $crud->callback_before_delete(array($this,'log_user_before_delete'));
                $crud->add_action('Pilih', 'show_button', base_url().'admin/agent/user/config/'.$in_userid.'/');
               // $crud->set_theme('datatables');
                $crud->unset_add();
                $crud->unset_export();
                $crud->unset_print();
                $crud->unset_delete();
                $crud->unset_edit();
                $crud->set_subject('Nasabah');

		$this->mTitle.= 'Config Agent -> Pilih Nasabah';
		$this->render_crud();
        }
        function add_field_callback_1()
        {
                unset($post_arr['USER_PASSWORD']);



            return $post_arr;


        }
        public function log_user_before_delete($primary_key)
        {
            $this->db->where('NASABAH_ID',$primary_key);
            $this->db->where('VERIFIKASI','1');
            $user = $this->db->get('nasabah')->row();

            if(empty($user)){
                return false;
            }
            else{
                return true;
            }
        }
        public function createnext($in_nsbid = NULL)
	{
		$form = $this->form_builder->create_form();

		if ($form->validate())
		{
			// passed validation
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			//$nasabahid = $this->input->post('nasabahid');
			$identity = empty($username) ? $email : $username;
			$additional_data = array(
				'first_name'	=> $this->input->post('first_name'),
				'last_name'		=> $this->input->post('last_name'),
			);
			$groups = $this->input->post('groups');

			// [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
			$this->ion_auth_model->tables = array(
				'users'				=> 'users',
				'groups'			=> 'groups',
				'users_groups'		=> 'users_groups',
				'login_attempts'	=> 'login_attempts',
			);

			// proceed to create user
			$user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups);			
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
                
		// get list of Frontend user groups
		$this->load->model('group_model', 'groups');
		$this->mViewData['groups'] = $this->groups->get_all();
		$this->mViewData['nasabahid'] = $in_nsbid;
		$this->mTitle = '[1019] Create User Agent';

		$this->mViewData['form'] = $form;
		$this->render('user/create');
	}

	// User Groups CRUD
	public function group()
	{
		$crud = $this->generate_crud('groups');
		$this->mTitle = 'User Groups';
		$this->render_crud();
	}

	// Frontend User Reset Password
	public function reset_password($user_id)
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
		$this->render('agent/user/reset_password');
	}
        public function Config($in_userid = NULL,$in_nasabahid) {
            if(empty($in_userid) || empty($in_nasabahid)){
                redirect('admin/panel/admin_user');
            }
                
            $res_data_nasabah = $this->Tab_model->Tab_by_nasabah($in_nasabahid);
            if($res_data_nasabah){
                foreach ($res_data_nasabah as $val) {
                    $in_norekening = $val->no_rekening;
                }
            }else{
                $this->messages->add('Failed, mohon periksa kembali', 'warning');
                redirect();
                return;
            }
            $this->load->model('Admin_user2_model');
            $cekexis_rekening = $this->Admin_user2_model->User_by_nasabahid($in_nasabahid,$in_norekening);
            if($cekexis_rekening){
                foreach ($cekexis_rekening as $v) {
                    $this->uexistname = $v->username ?: '';
                }
                $this->messages->add('Error, nomor rekening sudah digunakan oleh user -'.strtoupper($this->uexistname).' -, mohon koreksi kembali!', 'error');
                redirect('admin/panel/admin_user/read/'.$in_userid);
                return;
            }
            $arr_upd = array(
                'nasabah_id' => $in_nasabahid,
                'no_rekening' => $in_norekening,
            );
            $data = $this->Tab_model->upd_user($in_userid,$arr_upd);
            if($data){                
                $this->messages->add('Penambahan rekening sudah berhasil', 'succes');                
                redirect('admin/panel/admin_user/read/'.$in_userid);
            }else{
                $this->messages->add('Failed, mohon periksa kembali', 'warning');
                redirect('admin/panel/admin_user/read/'.$in_userid);
            }

            
        }
        public function Ts() {
            print_r($this->Trf_model->Cek_bank_default());
            
        }
}
