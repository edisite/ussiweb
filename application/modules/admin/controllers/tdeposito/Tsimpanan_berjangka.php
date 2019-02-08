<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tsimpanan_berjangka
 *
 * @author edisite
 */
class Tsimpanan_berjangka extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function Basil_pokok() {
       $crud = $this->generate_crud('deposito');       
        $crud->set_model('Deposito_nasabah_model');
        //$crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','JML_PINJAMAN','TGL_REALISASI','POKOK_SALDO_AKHIR','BUNGA_SALDO_AKHIR','VERIFIKASI','SALDO_BUNGA_YAD','SALDO_AKHIR_DEBIUS' );
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','TGL_MULAI','NO_REKENING_TABUNGAN','JML_DEPOSITO','SALDO_AKHIR_POKOK','SALDO_AKHIR_BUNGA',
                'SALDO_AKHIR_PAJAK','SALDO_AKHIR_ZAKAT','SALDO_AKHIR_TITIPAN','SALDO_AKHIR_CADANGAN','VERIFIKASI'
                );
        $crud->display_as('TGL_MULAI','TGL VALUTA');
        $crud->display_as('NO_REKENING_TABUNGAN','NO REKENING SIMPANAN');
        $crud->display_as('JML_DEPOSITO','NOMINAL');
        $crud->display_as('SALDO_AKHIR_POKOK','SALDO AKHIR');  
        $crud->display_as('SALDO_AKHIR_BUNGA', 'AKUMULASI BASIL');
        $crud->display_as('SALDO_AKHIR_PAJAK', 'AKUMULASI PAJAK');
        $crud->display_as('SALDO_AKHIR_ZAKAT', 'AKUMULASI ZAKAT');
        $crud->display_as('SALDO_AKHIR_TITIPAN', 'AKUMULASI TITIPAN');
        
        $crud->add_action('Pilih', 'Pilih', 'admin/tdeposito/tsimpanan_berjangka/basil_pokok_form', '');
        
        //$crud->where('tabung.VERIFIKASI', '1');
        $crud->callback_column('JML_DEPOSITO',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_POKOK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_PAJAK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_ZAKAT',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_TITIPAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_CADANGAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_BUNGA',array($this,'rp'));
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        //$crud->unset_view();
        $crud->set_subject('Nasabah deposito');
        $this->mTitle = "[6102] Transaksi Simpanan Berjangka[Basil-Pokok]";
        $this->render_crud();
    }
    
    public function Basil_pokok_form($no_rek) {
        $this->norek = $no_rek;
        $result_data_nasabah = $this->Dep_model->Dep_by_id($this->norek);
        //print_r($result_data_nasabah);
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $out_norek          = $sub_res->NO_REKENING;
                $out_nasabah_id     = $sub_res->NASABAH_ID;
                $out_nama_nasabah   = $sub_res->nama_nasabah;
                $out_alamat         = $sub_res->alamat;
                $out_des_produk     = $sub_res->DESKRIPSI_PRODUK;
                $out_tgl_mulai      = $sub_res->TGL_MULAI;
                $out_tgl_jt         = $sub_res->TGL_JT;
                $out_nominal        = $sub_res->SALDO_AKHIR_POKOK;
                $out_bunga          = $sub_res->SALDO_AKHIR_BUNGA;
                $out_pajak          = $sub_res->SALDO_AKHIR_PAJAK;                
            }
        }else{
            redirect();
        }
        
        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETNSBID']         = $out_nasabah_id;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETADDRS']         = $out_alamat;
        $this->mViewData['SETPRODK']         = $out_des_produk;
        $this->mViewData['SETTGLMU']         = $out_tgl_mulai;
        $this->mViewData['SETTGLJT']         = $out_tgl_jt;
        $this->mViewData['SETNOMIN']         = $out_nominal;
        $this->mViewData['SETBUNGA']         = $out_bunga;
        $this->mViewData['SETPAJAK']         = $out_pajak;
        $this->mViewData['SETDESCR']         = "304 - Pengambilan Basil OB ke nominal(pokok): ".$out_norek."[".$out_nama_nasabah."]";
        
        $this->mTitle = "[6102] Transaksi Simpanan Berjangka[Basil-Pokok]";
        $this->render('deposito/Tsimpananberjangka_basil_pokok');
    }
     function Rp($value, $row)
    {
        return number_format($value,2,",",".");
    }

    function _column_bonus_right_align($value,$row)
    {
        
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value,'Rp')."</span>";
    }
    function _column_center_align($value,$row)
    {
        
        return "<span style=\"width:100%;text-align:center;display:block;\">".$value."</span>";
    }
    public function Titipan () {
       $crud = $this->generate_crud('deposito');       
        $crud->set_model('Deposito_nasabah_model');
        //$crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','JML_PINJAMAN','TGL_REALISASI','POKOK_SALDO_AKHIR','BUNGA_SALDO_AKHIR','VERIFIKASI','SALDO_BUNGA_YAD','SALDO_AKHIR_DEBIUS' );
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','TGL_MULAI','NO_REKENING_TABUNGAN','JML_DEPOSITO','SALDO_AKHIR_POKOK','SALDO_AKHIR_BUNGA',
                'SALDO_AKHIR_PAJAK','SALDO_AKHIR_ZAKAT','SALDO_AKHIR_TITIPAN','SALDO_AKHIR_CADANGAN','VERIFIKASI'
                );
        $crud->display_as('TGL_MULAI','TGL VALUTA');
        $crud->display_as('NO_REKENING_TABUNGAN','NO REKENING SIMPANAN');
        $crud->display_as('JML_DEPOSITO','NOMINAL');
        $crud->display_as('SALDO_AKHIR_POKOK','SALDO AKHIR');  
        $crud->display_as('SALDO_AKHIR_BUNGA', 'AKUMULASI BASIL');
        $crud->display_as('SALDO_AKHIR_PAJAK', 'AKUMULASI PAJAK');
        $crud->display_as('SALDO_AKHIR_ZAKAT', 'AKUMULASI ZAKAT');
        $crud->display_as('SALDO_AKHIR_TITIPAN', 'AKUMULASI TITIPAN');
        
        $crud->add_action('Pilih', 'Pilih', 'admin/tdeposito/tsimpanan_berjangka/titipan_form', '');
        
        //$crud->where('tabung.VERIFIKASI', '1');
        $crud->callback_column('JML_DEPOSITO',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_POKOK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_PAJAK',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_ZAKAT',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_TITIPAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_CADANGAN',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_BUNGA',array($this,'rp'));
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        //$crud->unset_view();
        $crud->set_subject('Nasabah deposito');
        //$this->mTitle = "[3014] Transaksi Titipan Simpanan Berjangka";
        $this->mMenuID = '3014';
        $this->render_crud();
    }
      public function Titipan_form($no_rek) {
        $this->norek = $no_rek;
        $result_data_nasabah = $this->Dep_model->Dep_by_id($this->norek);
        //print_r($result_data_nasabah);
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $out_norek          = $sub_res->NO_REKENING;
                $out_nasabah_id     = $sub_res->NASABAH_ID;
                $out_nama_nasabah   = $sub_res->nama_nasabah;
                $out_alamat         = $sub_res->alamat;
                $out_des_produk     = $sub_res->DESKRIPSI_PRODUK;
                $out_tgl_mulai      = $sub_res->TGL_MULAI;
                $out_tgl_jt         = $sub_res->TGL_JT;
                $out_nominal        = $sub_res->SALDO_AKHIR_POKOK;
                $out_bunga          = $sub_res->SALDO_AKHIR_BUNGA;
                $out_pajak          = $sub_res->SALDO_AKHIR_PAJAK;                
            }
        }else{
            redirect();
        }
        
        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETNSBID']         = $out_nasabah_id;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETADDRS']         = $out_alamat;
        $this->mViewData['SETPRODK']         = $out_des_produk;
        $this->mViewData['SETTGLMU']         = $out_tgl_mulai;
        $this->mViewData['SETTGLJT']         = $out_tgl_jt;
        $this->mViewData['SETNOMIN']         = $out_nominal;
        $this->mViewData['SETBUNGA']         = $out_bunga;
        $this->mViewData['SETPAJAK']         = $out_pajak;
        $this->mViewData['SETDESCR']         = "400 - Pengambilan Titipan Basil Deposito Tunai : ".$out_norek."[".$out_nama_nasabah."]";
        
        $this->mTitle = "[3014] Transaksi Titipan Simpanan Berjangka";
        $this->render('deposito/Tsimpananberjangka_titipan');
    }
    
}
