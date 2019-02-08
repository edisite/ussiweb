<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Data_master_pembiayaan
 *
 * @author edisite
 */
class Data_master_pembiayaan extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('kredit');       
        $crud->set_model('Master_simpan_model');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','JML_PINJAMAN','TGL_REALISASI','POKOK_SALDO_AKHIR','BUNGA_SALDO_AKHIR','VERIFIKASI','SALDO_BUNGA_YAD','SALDO_AKHIR_DEBIUS' );
        $crud->display_as('SALDO_BUNGA_YAD','SALDO BASIL YAD');
        $crud->display_as('BUNGA_SALDO_AKHIR','BASIL SALDO AKHIR');
        //$crud->display_as('JML_PINJAMAN','JML PEMBIAYAAN');      
        //$crud->display_as("JML_PINJAMAN", "<span style='width: 100%; text-align: right; display: block;'>JML_PINJAMAN</span>");
        //$crud->callback_column('JML_PINJAMAN',array($this,'text-right'));
        
        $crud->callback_column('JML_PINJAMAN',array($this,'_column_bonus_right_align'));

        //$crud->callback_column('JML_PINJAMAN',array($this,'rp'));
        $crud->callback_column('POKOK_SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA_SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('SALDO_BUNGA_YAD',array($this,'_column_bonus_right_align'));
        $crud->callback_column('SALDO_AKHIR_DEBIUS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('VERIFIKASI',array($this,'_column_center_align'));

        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->set_subject('');
	$this->mTitle.= '[4000]Data Master Pembiayaan';
        $this->render_crud();

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

    public function Verifikasi() {
        $crud = $this->generate_crud('kredit');       
        $crud->set_model('Master_simpan_model');
        //$crud->set_relation('NASABAH_ID', 'nasabah',  '{NAMA_NASABAH}{ALAMAT}' );
       // $crud->join_relation('NASABAH_ID', 'tabung',  'NASABAH_ID');
        $crud->columns('NO_REKENING','NO_ALTERNATIF','NAMA_NASABAH','ALAMAT','JML_PINJAMAN','TGL_REALISASI','POKOK_SALDO_AKHIR','BUNGA_SALDO_AKHIR','VERIFIKASI','SALDO_BUNGA_YAD','SALDO_AKHIR_DEBIUS' );
        
        //$crud->fields('NO_REKENING','NASABAH_ID','SALDO_AKHIR','TGL_REGISTER','VERIFIKASI');
        /*$crud->display_as('BUNGA_BLN_NI','BASIL BULAN INI');*/
        $crud->display_as('SALDO_BUNGA_YAD','SALDO BASIL YAD');
        $crud->display_as('BUNGA_SALDO_AKHIR','BASIL SALDO AKHIR');
        $crud->display_as('JML_PINJAMAN','JML PEMBIAYAAN');      
        $crud->where('kredit.VERIFIKASI', '0');
        $crud->callback_column('JML_PINJAMAN',array($this,'rp'));
        $crud->callback_column('POKOK_SALDO_AKHIR',array($this,'rp'));
        $crud->callback_column('BUNGA_SALDO_AKHIR',array($this,'rp'));
        $crud->callback_column('SALDO_BUNGA_YAD',array($this,'rp'));
        $crud->callback_column('SALDO_AKHIR_DEBIUS',array($this,'rp'));
        
       // $crud->basic_model->set_query_str();
        $crud->unset_export();
        $crud->unset_print();
        //$crud->unset_add();
        $crud->set_subject('');
	$this->mTitle.= '[4001]Data Master Pembiayaan';
        $this->render_crud();
        //$this->_Getlist($output);
    }
}
