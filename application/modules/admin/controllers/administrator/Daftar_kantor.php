<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Daftar_kantor
 *
 * @author edisite
 */
class Daftar_kantor extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('app_kode_kantor');
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_delete();
        $this->mTitle .= '[9024] Daftar Kantor';
        $this->render_crud();
    }
}
