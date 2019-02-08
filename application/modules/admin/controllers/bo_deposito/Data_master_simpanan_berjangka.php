<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data_master_simpanan_berjangka
 *
 * @author edisite
 */
class Data_master_simpanan_berjangka extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        
        $this->mTitle = 'BO DEPOSITO - ';
    }
    public function index() {
        $crud = $this->generate_crud('deposito');       
        $crud->set_model('Master_simpan_model');
        //$crud->set_relation('NASABAH_ID', 'nasabah',  '{NAMA_NASABAH}{ALAMAT}' );
        // $crud->join_relation('NASABAH_ID', 'tabung',  'NASABAH_ID');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','TGL_MULAI','NO_REKENING_TABUNGAN','JML_DEPOSITO','SALDO_AKHIR_POKOK','SALDO_AKHIR_BUNGA',
                'SALDO_AKHIR_PAJAK','SALDO_AKHIR_ZAKAT','SALDO_AKHIR_TITIPAN','SALDO_AKHIR_CADANGAN','VERIFIKASI'
                );
        
        //eposito.no_rekening, nasabah.nama_nasabah,alamat, jml_deposito, saldo_akhir_pokok, saldo_akhir_bunga,deposito.verifikasi, 
        //no_rekening_tabungan, saldo_akhir_bunga, saldo_akhir_pajak, saldo_akhir_zakat,saldo_akhir_cadangan,saldo_akhir_titipan_pajak, 
        //saldo_akhir_titipan,no_alternatif,tgl_mulai
        
        //$crud->fields('NO_REKENING','NASABAH_ID','SALDO_AKHIR','TGL_REGISTER','VERIFIKASI');
        $crud->display_as('TGL_MULAI','TGL VALUTA');
        $crud->display_as('NO_REKENING_TABUNGAN','NO REKENING SIMPANAN');
        $crud->display_as('JML_DEPOSITO','NOMINAL');
        $crud->display_as('SALDO_AKHIR_POKOK','SALDO AKHIR');  
        $crud->display_as('SALDO_AKHIR_BUNGA', 'AKUMULASI BASIL');
        $crud->display_as('SALDO_AKHIR_PAJAK', 'AKUMULASI PAJAK');
        $crud->display_as('SALDO_AKHIR_ZAKAT', 'AKUMULASI ZAKAT');
        $crud->display_as('SALDO_AKHIR_TITIPAN', 'AKUMULASI TITIPAN');
        
        //$crud->where('tabung.VERIFIKASI', '1');
        $crud->callback_column('JML_DEPOSITO',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_POKOK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_PAJAK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_ZAKAT',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_TITIPAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_CADANGAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_BUNGA',array($this,'rp'));
        
       // $crud->basic_model->set_query_str();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->set_subject('NASABAH');
	$this->mTitle.= '[3000]DATA MASTER SIMPANAN BERJANGKA';
        $this->mMenuID = '3000';
        $this->render_crud();
        //$this->_Getlist($output);
        
        
        
    }
    function Rp($value, $row)
    {
        return number_format($value,2,",",".");
    }
    public function Verifikasi() {
        $crud = $this->generate_crud('deposito');       
        $crud->set_model('Master_simpan_model');
        //$crud->set_relation('NASABAH_ID', 'nasabah',  '{NAMA_NASABAH}{ALAMAT}' );
        // $crud->join_relation('NASABAH_ID', 'tabung',  'NASABAH_ID');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','TGL_MULAI','NO_REKENING_TABUNGAN','JML_DEPOSITO','SALDO_AKHIR_POKOK','SALDO_AKHIR_BUNGA',
                'SALDO_AKHIR_PAJAK','SALDO_AKHIR_ZAKAT','SALDO_AKHIR_TITIPAN','SALDO_AKHIR_CADANGAN','VERIFIKASI'
                );
        
        //eposito.no_rekening, nasabah.nama_nasabah,alamat, jml_deposito, saldo_akhir_pokok, saldo_akhir_bunga,deposito.verifikasi, 
        //no_rekening_tabungan, saldo_akhir_bunga, saldo_akhir_pajak, saldo_akhir_zakat,saldo_akhir_cadangan,saldo_akhir_titipan_pajak, 
        //saldo_akhir_titipan,no_alternatif,tgl_mulai
        
        //$crud->fields('NO_REKENING','NASABAH_ID','SALDO_AKHIR','TGL_REGISTER','VERIFIKASI');
        $crud->display_as('TGL_MULAI','TGL VALUTA');
        $crud->display_as('NO_REKENING_TABUNGAN','NO REKENING SIMPANAN');
        $crud->display_as('JML_DEPOSITO','NOMINAL');
        $crud->display_as('SALDO_AKHIR_POKOK','SALDO AKHIR');  
        $crud->display_as('SALDO_AKHIR_BUNGA', 'AKUMULASI BASIL');
        $crud->display_as('SALDO_AKHIR_PAJAK', 'AKUMULASI PAJAK');
        $crud->display_as('SALDO_AKHIR_ZAKAT', 'AKUMULASI ZAKAT');
        $crud->display_as('SALDO_AKHIR_TITIPAN', 'AKUMULASI TITIPAN');
        
        $crud->where('deposito.VERIFIKASI', '0');
        $crud->callback_column('JML_DEPOSITO',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_POKOK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_PAJAK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_ZAKAT',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_TITIPAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_CADANGAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_BUNGA',array($this,'rp'));
        
       // $crud->basic_model->set_query_str();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->set_subject('NASABAH');
	//$this->mTitle.= '[2001]VERIFIKASI DATA MASTER SIMPANAN BERJANGKA';
        $this->mMenuID = '2001';
        $this->render_crud();
        //$this->_Getlist($output);
        
    }
    
    public function Def_produk()  {//Def_produk_simpanan_berjangka
        
        $crud = $this->generate_crud('dep_produk');
        $crud->set_theme('datatables');
        $crud->columns('KODE_PRODUK','DESKRIPSI_PRODUK','SUKU_BUNGA_DEFAULT','PPH_DEFAULT','methode_hitung_bunga');
        $crud->display_as('KODE_PRODUK', 'KODE');
        $crud->display_as('DESKRIPSI_PRODUK', 'Deskripsi');
        $crud->display_as('SUKU_BUNGA_DEFAULT', 'JKW');
        $crud->display_as('PPH_DEFAULT', 'PPh');
        $crud->display_as('methode_hitung_bunga', 'Metode hitung bunga');        
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_delete();
        //$crud->unset_view();
                
        $crud->set_subject('Produk');
	$this->mTitle.= '[3002]PRODUK SIMPANAN BERJANGKA';
        $this->mMenuID = '3002';
        $this->render_crud();
    }
    public function Sett_config() {
        $crud = $this->generate_crud('dep_integrasi');
        $crud->set_theme('datatables');
        $crud->columns('KODE_INTEGRASI','DESKRIPSI_INTEGRASI','KODE_PERK_KAS','KODE_PERK_HUTANG_POKOK',
                'KODE_PERK_HUTANG_BUNGA','KODE_PERK_BIAYA_BUNGA','KODE_PERK_PAJAK_BUNGA','KODE_PERK_PEND_ADM'
                ,'KODE_PERK_TITIPAN_BUNGA','KODE_PERK_BDD','kode_perk_zakat','KODE_PERK_CADANGAN_BUNGA');
        $crud->display_as('KODE_INTEGRASI', 'KODE');
        $crud->display_as('DESKRIPSI_INTEGRASI', 'DESKRIPSI');
        $crud->display_as('KODE_PERK_HUTANG_POKOK', 'KODE PERK SIMP BERJANGKA');
        $crud->display_as('KODE_PERK_HUTANG_BUNGA', 'KODE PERK BASIL YMH DI BAYAR');
        $crud->display_as('KODE_PERK_BIAYA_BUNGA', 'KODE PERK BIAYA BAGI HASIL');
        $crud->display_as('KODE_PERK_PAJAK_BUNGA', 'KODE PERK PAJAK BAGI HASIL');
        $crud->display_as('kode_perk_zakat', 'KODE PERK ZAKAT');
        $crud->display_as('KODE_PERK_TITIPAN_BUNGA', 'KODE PERK TITIPAN BAGI HASIL');
        $crud->display_as('KODE_PERK_CADANGAN_BUNGA', 'KODE PERK CADANGAN');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Parameter');
	$this->mTitle.= '[3003]Setting Konfigurasi INTEGRASI SIMPANAN';
        $this->mMenuID = '3003';
        $this->render_crud();
    }
}
