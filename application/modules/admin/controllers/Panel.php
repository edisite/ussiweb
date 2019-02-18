<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Panel management, includes: 
 * 	- Admin Users CRUD
 * 	- Admin User Groups CRUD
 * 	- Admin User Reset Password
 * 	- Account Settings (for login user)
 */
class Panel extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
		$this->mTitle = 'Admin Panel - ';
	}

	// Admin Users CRUD
	public function admin_user()
	{
		$crud = $this->generate_crud('admin_users');
                $crud->set_theme('datatables');
		$crud->columns('id', 'username','groups', 'first_name', 'last_name', 'active');
		$crud->fields('username', 'id','first_name', 'last_name', 'active','penerimaan','pengeluaran','pin_payment','msisdn_payment','model_agent');
		$this->unset_crud_fields('ip_address', 'last_login');
                $crud->display_as('id', 'User id');
		// cannot change Admin User groups once created
		if ($crud->getState()=='list')
		{
			$crud->set_relation_n_n('groups', 'admin_users_groups', 'admin_groups', 'user_id', 'group_id', 'name');
		}

		// only webmaster can reset Admin User password
		if ( $this->ion_auth->in_group(array('webmaster','admin','manager')) )
		{
			$crud->add_action('Passwd', '', 'admin/panel/admin_user_reset_password', 'fa fa-repeat');
			$crud->add_action('Web', '', 'admin/panel/menu', 'fa fa-list');
			$crud->add_action('Mobile', '', 'admin/panel/menu_mob', 'fa fa-list');
                        $crud->add_action('Rek', '', 'admin/agent/user/create', 'fa fa-user');
                }
		
		// disable direct create / delete Admin User
		$crud->unset_export();
                $crud->unset_print();   
                $crud->unset_add();
		$crud->unset_delete();
		//$crud->unset_edit();

		$this->mTitle.= 'Admin Users';
		$this->render_crud();
	}

	// Create Admin User
	public function admin_user_create()
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
//                                $this->load->model('Sys_daftar_user_menu_model');
//                                if($this->Sys_daftar_user_menu_model->menu_register($username)){
				// success
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);
//                                }else{
//                                $errors = "error register menu";
//				$this->system_message->set_error($errors);
//                                }
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

	// Admin User Groups CRUD
	public function admin_user_group()
	{
		$crud = $this->generate_crud('admin_groups');
		$this->mTitle.= 'Admin User Groups';
		$this->render_crud();
	}

	// Admin User Reset password
	public function admin_user_reset_password($user_id)
	{
		// only top-level users can reset Admin User passwords
		$this->verify_auth(array('webmaster'));

		$form = $this->form_builder->create_form();
		if ($form->validate())
		{
			// pass validation
			$data = array('password' => $this->input->post('new_password'));
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

		$this->load->model('admin_user_model', 'admin_users');
		$target = $this->admin_users->get($user_id);
		$this->mViewData['target'] = $target;

		$this->mViewData['form'] = $form;
		$this->mTitle.= 'Reset Admin User Password';
		$this->render('panel/admin_user_reset_password');
	}

	// Account Settings
	public function account()
	{
		// Update Info form
		$form1 = $this->form_builder->create_form('admin/panel/account_update_info');
		$form1->set_rule_group('panel/account_update_info');                
                $this->mViewData['form1'] = $form1;

		// Change Password form
		$form2 = $this->form_builder->create_form('admin/panel/account_change_password');
		$form1->set_rule_group('panel/account_change_password');
		$this->mViewData['form2'] = $form2;

		$this->mTitle = "Account Settings";
		$this->render('panel/account');
	}

	// Submission of Update Info form
	public function account_update_info()
	{
		$data = $this->input->post();
		if ($this->ion_auth->update($this->mUser->id, $data))
		{
			$messages = $this->ion_auth->messages();
			$this->system_message->set_success($messages);
		}
		else
		{
			$errors = $this->ion_auth->errors();
			$this->system_message->set_error($errors);
		}

		redirect('admin/panel/account');
	}

	// Submission of Change Password form
	public function account_change_password()
	{
		$data = array('password' => $this->input->post('new_password'));
		if ($this->ion_auth->update($this->mUser->id, $data))
		{
			$messages = $this->ion_auth->messages();
			$this->system_message->set_success($messages);
		}
		else
		{
			$errors = $this->ion_auth->errors();
			$this->system_message->set_error($errors);
		}

		redirect('admin/panel/account');
	}
	
	/**
	 * Logout user
	 */
	public function logout()
	{
		$this->ion_auth->logout();
		redirect('admin/login');
	}
        public function menu($userid = '') {
            if(empty($userid)){
                redirect('admin/panel/admin_user');
            }
            $this->load->model('Admin_user2_model');
            $this->load->model('Sys_daftar_user_menu_model');
            $duserid     = $this->Admin_user2_model->id($userid);
            foreach ($duserid as $val) {
                $userid   = $val->id;
                $userpegawai   = $val->username;
                $emplname1   = $val->first_name;
                $emplname2   = $val->last_name;
            }
            $datamenu = $this->Sys_daftar_user_menu_model->get_menu($userid);
            $datamenu_src = $this->Sys_daftar_user_menu_model->get_menu_src($userpegawai);
            
            $this->mViewData['userid']     = $userid;
            $this->mViewData['userkey']     = $userpegawai;
            $this->mViewData['datamenu']    = $datamenu;
            $this->mViewData['datamenu_src']= $datamenu_src;
            $this->mViewData['employe']     = ucfirst($emplname1.' '.$emplname2);
            $dlistuname                     = $this->Admin_user2_model->Usergroup();
            $this->mViewData['username']    = $dlistuname;
            $this->mTitle                   = "Account Settings";
            $this->render('panel/menu_nested');
            
        }
        public function menu_mob($userid = '') {
            if(empty($userid)){
                redirect('admin/panel/admin_user');
            }
            $this->load->model('Admin_user2_model');
            $this->load->model('Sys_daftar_user_menu_model');
            $duserid     = $this->Admin_user2_model->id($userid);
            foreach ($duserid as $val) {
                $userid   = $val->id;
                $userpegawai   = $val->username;
                $emplname1   = $val->first_name;
                $emplname2   = $val->last_name;
            }
            $datamenu = $this->Sys_daftar_user_menu_model->get_menu_mobile($userpegawai);
            $datamenu_src = $this->Sys_daftar_user_menu_model->get_menu_mobile_src($userpegawai);
            
            $this->mViewData['userid']     = $userid;
            $this->mViewData['userkey']     = $userpegawai;
            $this->mViewData['datamenu']    = $datamenu;
            $this->mViewData['datamenu_src']= $datamenu_src;
            $this->mViewData['employe']     = ucfirst($emplname1.' '.$emplname2);
            $dlistuname                     = $this->Admin_user2_model->Usergroup();
            $this->mViewData['username']    = $dlistuname;
            $this->mTitle                   = "Account Settings";
            $this->render('panel/menu_nested_mobile');
            
        }
        public function menu_duplicat($userori,$usercopy) {
            $this->load->model('Sys_daftar_user_menu_model');     
             $this->load->model('Admin_user2_model');
            
            $this->Sys_daftar_user_menu_model->menu_duplicat($userori,$usercopy);            
            redirect('admin/panel/menu/'.$userori);
        }
        public function Menu_add() {            
            $in_menuid = $this->input->post('menuid') ?: '';
            $in_userid = $this->input->post('userid') ?: '';
            $in_username = $this->input->post('userkey') ?: '';
            //print_r($in_menuid);
            if(empty($in_menuid)){
                $this->messages->add('List Menu invalid','error');
                redirect('admin/panel/admin_user/');
            }
            if(empty($in_username)){
                $this->messages->add('Account invalid','error');
                redirect('admin/panel/admin_user/');
            }
            $out_menuid = json_decode($in_menuid,true);            
            $arr = array();
            $get_menu_id = 1;
            foreach($out_menuid as $val=>$v){
                 //$get_menu_id       = $v['id'] ?: '';
                 $get_group_name    = $v['name'] ?: '';
                 if(empty($get_menu_id) || empty($get_group_name)){                   
                 }else{
                    $get_child_id = 1;
                    foreach ($v['children'] as $key => $cval) {
                        //$get_child_id      = $cval['id'];
                        $get_child_menuid  = $cval['menuid'];                     
                        $arr[]= array(
                               'USER_NAME'     => $in_username, 
                               'URUTAN_GROUP'  => $get_menu_id, 
                               'MENU_GROUP'    => $get_group_name, 
                               'URUTAN_MENU'   => $get_child_id, 
                               'MENU_ID'       => $get_child_menuid, 
                               'flag'          => '1'
                        );
                        $get_child_id ++;
                    }
                 }
                 $get_menu_id ++;
            }
            if(empty($arr)){
                $this->messages->add('Terjadi kesalahan','error');
                redirect('admin/panel/admin_user/');
            }            
            if(!is_array($arr)){
                $this->messages->add('Terjadi kesalahan','error');
                redirect('admin/panel/admin_user/');
            }
            //hapus yanglama
            $this->load->model('Sys_daftar_user_menu_model');
            $this->Sys_daftar_user_menu_model->del_menu_def($in_username);
            $this->Tab_model->Ins_batch('sys_user_menu_def',$arr);
            $this->messages->add('Succesfully','suces');
            redirect('admin/panel/menu/'.$in_userid);
        }
        public function Menu_mobile_add() {            
            $in_menuid = $this->input->post('menuid') ?: '';
            $in_userid = $this->input->post('userid') ?: '';
            $in_username = $this->input->post('userkey') ?: '';
            //print_r($in_menuid);
            if(empty($in_menuid)){
                $this->messages->add('List Menu invalid','error');
                redirect('admin/panel/admin_user/');
            }
            if(empty($in_username)){
                $this->messages->add('Account invalid','error');
                redirect('admin/panel/admin_user/');
            }
            $out_menuid = json_decode($in_menuid,true);  
            $arr = array();
            $get_menu_id = 1;
            foreach($out_menuid as $val=>$v){
                    $get_child_menuid  = $v['menuid'];   
                    if(empty($get_menu_id) || empty($get_child_menuid)){                   
                    }else{
                        $arr[]= array(
                               'USER_NAME'     => $in_username, 
                               'URUTAN_GROUP'  => '', 
                               'MENU_GROUP'    => '', 
                               'URUTAN_MENU'   => $get_menu_id, 
                               'MENU_ID'       => $get_child_menuid, 
                               'flag'          => '1'
                        ); 
                    }
                 $get_menu_id ++;
            }           
            if(empty($arr)){
                $this->messages->add('Terjadi kesalahan','error');
                redirect('admin/panel/admin_user/');
            }            
            if(!is_array($arr)){
                $this->messages->add('Terjadi kesalahan','error');
                redirect('admin/panel/admin_user/');
            }
            //hapus yanglama
            $this->load->model('Sys_daftar_user_menu_model');
            $this->Sys_daftar_user_menu_model->del_menu_def_mobile($in_username);
            $this->Tab_model->Ins_batch('sys_user_menu_def_mobile',$arr);
            $this->messages->add('Succesfully','suces');
            redirect('admin/panel/menu_mob/'.$in_userid);
        }
        public function Menu_new_group($in_uname,$in_userid) {
            if(empty($in_uname) || empty($in_userid)){
                    redirect('aa');
                }
            $this->load->model('Admin_user2_model');
            if(!$this->Admin_user2_model->User_by_username($in_uname)){
                redirect('tes');
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('grpmenu','Menu','required'); 
            if($this->form_validation->run()===FALSE){
                $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
                $this->mTitle.= 'Group Menu';
                $this->render('panel/menu_new_group'); 
            }else{
			// passed validation
			$in_groupmenu   = $this->input->post('grpmenu') ?: 'tes';
                       
                        $arr = array(
                               'USER_NAME'     => $in_uname, 
                               'URUTAN_GROUP'  => '', 
                               'MENU_GROUP'    => $in_groupmenu, 
                               'URUTAN_MENU'   => '', 
                               'MENU_ID'       => '', 
                               'flag'          => '1'
                        );
                        if($this->Tab_model->Ins_tbl('sys_user_menu_def',$arr)){
                            $this->messages->add('success','info');
                            redirect('admin/panel/menu/'.$in_userid);
                        }else{
                            $this->messages->add('error','warning');
                        }
			
		}
        }
}
