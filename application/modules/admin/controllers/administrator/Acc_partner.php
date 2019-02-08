<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of acc_partner
 *
 * @author edisite
 */
class Acc_partner extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('tab_rek_partner');
        $crud->set_theme('datatables');
        //$crud->where('(KEYNAME LIKE "%%" OR KEYVALUE LIKE "%%")',NULL,FALSE);
        //$crud->where('group_id<>"SYS"',NULL,FALSE);
        //$crud->columns('GROUP_ID','KEYNAME','KEYVALUE','KETERANGAN');
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Parameter');
	$this->mTitle.= '[9003] Setting Parameter';
        
        $this->render_crud();
        
                
    }
}
