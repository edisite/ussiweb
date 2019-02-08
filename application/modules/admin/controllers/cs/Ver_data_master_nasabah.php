<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ver_data_master_nasabah
 *
 * @author edisite
 */
class Ver_data_master_nasabah extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('nasabah');
        $crud->where('VERIFIKASI','0');
        $crud->or_where('VERIFIKASI', 'IS NULL');
        $crud->columns('NASABAH_ID','NAMA_NASABAH','ALAMAT', 'VERIFIKASI', 'DIN');
        $crud->unset_add();
        $crud->set_subject('Verifikasi Data');
	$this->mTitle.= '[1001]Verifikasi Data Master Nasabah';
        $crud->fields('NASABAH_ID', 'NAMA_NASABAH', 'ALAMAT', 'VERIFIKASI','DIN');
        $crud->change_field_type('NASABAH_ID','invisible');
        $this->render_crud();
    }
}
