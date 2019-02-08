<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Def_produk_pembiayaan
 *
 * @author edisite
 */
class Def_produk_pembiayaan extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('kre_produk');       
        $crud->columns('kode_produk','DESKRIPSI_PRODUK','TYPE_KREDIT_DEFAULT','kode_pembiayaan','FORMAT_KARTU');
       
        //$crud->fields('AGUNAN_ID','KODE_JENIS_AGUNAN','DESKRIPSI_RINGKAS','VERIFIKASI');
        $crud->display_as('kode_produk','KODE');   
         $crud->display_as('DESKRIPSI_PRODUK','DESKRIPSI');   
          $crud->display_as('TYPE_KREDIT_DEFAULT','TYPE PEMBIAYAAN');   
           $crud->display_as('kode_pembiayaan','KODE PEMBIAYAAN');   
            
        //$crud->where('kre_agunan.VERIFIKASI', '0');
        //  $crud->or_where('kre_agunan.VERIFIKASI is NULL');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_delete();
        $crud->set_subject('');
	$this->mTitle.= '[4004]Definisi produk pembiayaan';
        $this->render_crud();
        //$this->_Getlist($output);
    }
}
