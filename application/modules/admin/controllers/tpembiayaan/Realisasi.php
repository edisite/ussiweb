<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tpembiayaan
 *
 * @author edisite
 */
class Realisasi extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('kredit');
        $crud->set_model('Pembiayaan_nasabah_model');
        $crud->columns('NO_REKENING', 'NO_ALTERNATIF', 'NAMA_NASABAH','ALAMAT', 'JML_PINJAMAN', 'TGL_REALISASI', 'POKOK_SALDO_AKHIR'
                ,'VERIFIKASI','SALDO_BUNGA_YAD','SALDO_AKHIR_DEBIUS');
        
        $crud->unset_add();
        $crud->unset_print();
        $crud->unset_export();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->add_action('Pilih', 'Pilih', 'admin/tpembiayaan/Realisasi/Pencairan_form', '');        
        $crud->callback_column('JML_PINJAMAN',array($this,'_column_bonus_right_align'));
        $crud->callback_column('POKOK_SALDO_AKHIR',array($this,'_column_bonus_right_align'));
        $crud->callback_column('SALDO_BUNGA_YAD',array($this,'_column_bonus_right_align'));
        $crud->callback_column('SALDO_AKHIR_DEBIUS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('TGL_REALISASI',array($this,'Dtm_format'));
        $crud->set_subject('Realiasi Peminjaman');
	$this->mTitle.= '[6200] Transaksi Pembiayaan[ Realisasi ]';
        $this->render_crud(); 
        
    }
        function Rp($value, $row)
    {
        return number_format($value,2,",",".");
    }
    function Rp1($value, $row)
    {
        return "Rp ".number_format($value,2,",",".");
    }
    function convert_to_number($rupiah)
    {
            return intval(preg_replace('/,.*|[^0-9]/', '', $rupiah));
    }
     function _column_bonus_right_align($value,$row)
    {
        
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value,'Rp')."</span>";
    }
    function _column_center_align($value,$row)
    {
        
        return "<span style=\"width:100%;text-align:center;display:block;\">".$value."</span>";
    }
    function Dtm_format($tgl) {
        $newDate = date("d-m-Y", strtotime($tgl));
        return $newDate;
    }
    
    public function Pencairan_form($in_norek) {
        $this->mTitle = "[6000] Transaksi Simpanan";
        $this->render('kredit/realisasi');
    }

}
