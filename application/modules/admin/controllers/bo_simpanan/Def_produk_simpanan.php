<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Def_produk_simpanan
 *
 * @author edisite
 */
class Def_produk_simpanan extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        
        $this->mTitle = 'BO SIMPANAN - ';
    }
    
    public function index() {
        $crud = $this->generate_crud('tab_produk');
        $crud->set_theme('datatables');
        $crud->columns('KODE_PRODUK','DESKRIPSI_PRODUK','SUKU_BUNGA_DEFAULT','PPH_DEFAULT','ADM_PER_BULAN','SETORAN_MINIMUM_DEFAULT','SALDO_MIN_BUNGA','METODE_HITUNG_BUNGA','FORMAT_BUKU','FORMAT_KARTU','ADM_PENUTUPAN','KODE_SIMPANAN');
        
        $crud->callback_column('SETORAN_MINIMUM_DEFAULT',array($this,'rp'));
        $crud->callback_column('SALDO_MIN_BUNGA',array($this,'rp'));
        $crud->callback_column('ADM_PENUTUPAN',array($this,'rp'));
        //$crud->callback_column('SUKU_BUNGA_DEFAULT',array($this,'flt'));
        
        $crud->display_as('KODE_PRODUK', 'KODE');
        $crud->display_as('DESKRIPSI_PRODUK', 'DESKRIPSI');
        $crud->display_as('SUKU_BUNGA_DEFAULT', 'NISBAH');
        $crud->display_as('PPH_DEFAULT', 'PPH');
        $crud->display_as('ADM_PER_BULAN', 'ADM');
        $crud->display_as('SETORAN_MINIMUM_DEFAULT', 'SETORAN_MINIMUM');
        $crud->display_as('SALDO_MIN_BUNGA', 'SALDO MIN DAPAT BUNGA');
        $crud->display_as('METODE_HITUNG_BUNGA', 'METODE HITUNG BASIL');
        
        $crud->unset_add();
        //$crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('PRODUK');
	$this->mTitle.= '[2002]DEFINISI PRODUK SIMPANAN';
        $this->render_crud();
    }
    function Rp($value, $row)
    {
        return "Rp ".number_format($value,2,",",".");
    }
    function Flt($value, $row)
    {
        return number_format($value,1,",",".");
    }
    
}
