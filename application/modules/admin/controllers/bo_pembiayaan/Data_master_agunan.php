<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data_master_angunan
 *
 * @author edisite
 */
class Data_master_agunan extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('kre_agunan');       
        $crud->columns('AGUNAN_ID','KODE_JENIS_AGUNAN','DESKRIPSI_RINGKAS','VERIFIKASI');
       
        $crud->fields('AGUNAN_ID','KODE_JENIS_AGUNAN','DESKRIPSI_RINGKAS','VERIFIKASI');
        $crud->display_as('KODE_JENIS_AGUNAN','JENIS');      

        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->set_subject('');
	$this->mTitle.= '[4002]Data Master Agunan';
        $this->render_crud();
        //$this->_Getlist($output);
    }
    function Rp($value, $row)
    {
        return number_format($value,2,",",".");
    }
       public function Verifikasi() {
        $crud = $this->generate_crud('kre_agunan');       
        $crud->columns('AGUNAN_ID','KODE_JENIS_AGUNAN','DESKRIPSI_RINGKAS','VERIFIKASI');
       
        $crud->fields('AGUNAN_ID','KODE_JENIS_AGUNAN','DESKRIPSI_RINGKAS','VERIFIKASI');
        $crud->display_as('KODE_JENIS_AGUNAN','JENIS');      
        $crud->where('kre_agunan.VERIFIKASI', '0');
        $crud->or_where('kre_agunan.VERIFIKASI is NULL');
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->set_subject('');
	$this->mTitle.= '[4003]Verifikasi Data Master Agunan';
        $this->render_crud();
        //$this->_Getlist($output);
    }
}
