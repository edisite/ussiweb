<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Monitor
 *
 * @author edisite
 */
class Monitor extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Access() {
        $crud = $this->generate_crud('api_access');
        $crud->set_theme('datatables');
        // disable direct create / delete Admin User
        $crud->unset_export();
        $crud->unset_print();   
//        $crud->unset_add();
//        $crud->unset_delete();
        //$crud->unset_edit();

        $this->mTitle.= 'Control Access';
        $this->render_crud();
    }
    public function Keys() {
        $crud = $this->generate_crud('api_keys');
        $crud->set_theme('datatables');
        // disable direct create / delete Admin User
        $crud->unset_export();
        $crud->unset_print();   
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $this->mTitle.= 'Control Access';
        $this->render_crud();
    }
    public function Limits() {
        $crud = $this->generate_crud('api_limits');
        $crud->set_theme('datatables');
        // disable direct create / delete Admin User
        $crud->unset_export();
        $crud->unset_print();   
        $crud->unset_add();
//        $crud->unset_delete();
        //$crud->unset_edit();

        $this->mTitle.= 'Control Access';
        $this->render_crud();
    }
    public function Logs() {
        $crud = $this->generate_crud('api_logs');
        $crud->set_theme('datatables');
        // disable direct create / delete Admin User
        $crud->unset_export();
        $crud->unset_print();   
        $crud->unset_add();
//        $crud->unset_delete();
        //$crud->unset_edit();

        $this->mTitle.= 'Control Access';
        $this->render_crud();
        
    }
    public function Keys_ses() {
        $crud = $this->generate_crud('api_kunci');
        $crud->set_theme('datatables');
        // disable direct create / delete Admin User
        $crud->unset_export();
        $crud->unset_print();   
        $crud->unset_add();
//        $crud->unset_delete();
        //$crud->unset_edit();

        $this->mTitle.= 'Session Key';
        $this->render_crud();
        
    }
}
