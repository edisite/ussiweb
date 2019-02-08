<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Editnamamenu
 *
 * @author edisite
 */
class Editnamamenu extends Admin_Controller{
    //put your code here
      public function __construct() {
          
        parent::__construct();
        $this->load->library('form_builder');
        
    }
    public function index() {
        $crud = $this->generate_crud('sys_daftar_menu');
                //$crud->set_theme('datatables');
		$crud->columns('MENU_ID', 'MENU_PROMPT', 'MENU_DESCRIPTION', 'MENU_FORM', 'MENU_SCRIPT', 'MENU_REPORT', 'MENU_PARAMETER1', 'MENU_PARAMETER2','FLAG');
                
                $crud->display_as('MENU_FORM','MENU FORM');
                $crud->display_as('MENU_SCRIPT','SCRIPT ID');
                $crud->display_as('MENU_REPORT','REPORT ID');
                $crud->display_as('MENU_PARAMETER1','PARAM#1');
                $crud->display_as('MENU_PARAMETER2','PARAM#2');
                $crud->set_rules('MENU_ID', 'MENU ID', 'NUMERIC');
                //$crud->change_field_type('FLAG', 'enum', array('0' => 'INACTIVE','0' => 'ACTIVE'));
               // $crud->required_fields('MENU_ID','MENU_PROMPT','MENU_DESCRIPTION', 'MENU_FORM');
               //$crud->change_field_type('MENU_ID','invisible');
		// disable direct create / delete Admin User
                $crud->set_subject('MENU');                
		$this->mTitle.= '[9002]DAFTAR MENU WEB';
		$this->render_crud();
    }
    public function Mobile() {
        $crud = $this->generate_crud('sys_daftar_menu_mobile');
                $crud->set_theme('datatables');
		$crud->columns('MENU_ID', 'MENU_PROMPT', 'MENU_DESCRIPTION', 'MENU_FORM', 'MENU_SCRIPT', 'MENU_REPORT', 'MENU_PARAMETER1', 'MENU_PARAMETER2','FLAG');
                
                $crud->display_as('MENU_FORM','MENU FORM');
                $crud->display_as('MENU_SCRIPT','SCRIPT ID');
                $crud->display_as('MENU_REPORT','REPORT ID');
                $crud->display_as('MENU_PARAMETER1','PARAM#1');
                $crud->display_as('MENU_PARAMETER2','PARAM#2');
                $crud->set_rules('MENU_ID', 'MENU ID', 'NUMERIC');
                $crud->set_subject('MENU');
                
		$this->mTitle.= '[1034]DAFTAR MENU MOBILE';
                $this->mMenuID = '1034';
		$this->render_crud();
    }
}
