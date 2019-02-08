<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Browse_pembiayaan
 *
 * @author edisite
 */
class Browse_kretrans extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Index() {
       $crud = $this->generate_crud('kretrans');       
        $crud->set_model('Kretrans_nasabah_model');
        $crud->columns('KRETRANS_ID','TGL_TRANS','NO_REKENING','NAMA_NASABAH','MY_KODE_TRANS','KODE_TRANS','KETERANGAN','KUITANSI',
                'POKOK','BUNGA','DENDA','PROVISI','MATERAI','PREMI','NOTARIEL','ADM_LAINYA',
                'KODE_KANTOR','USERID','VERIFIKASI','ANGSURAN_KE','NO_REKENING_TABUNGAN','KODE_PERK_OB','KODE_PERK_RAK','POSTED_TO_GL','rek_tabungan_premi','rek_tab_notariel','bunga_yad','kolek');

        $crud->display_as('NAMA_NASABAH','NAMA');
        $crud->display_as('BUNGA_TRANS','BASIL');
        $crud->display_as('DEPTRANS_ID','ID');
        $crud->display_as('PAJAK_TRANS','PAJAK');
        $crud->display_as('zakat_trans','ZAKAT');
        $crud->display_as('PAJAK_TRANS','PAJAK');
        $crud->display_as('TITIPAN_TRANS','TITIPAN');
        $crud->display_as('USERID','USER ID');
        $crud->display_as('NO_REKENING_TABUNGAN','NO REK TABUNGAN');
        $crud->display_as('CADANGAN_TRANS','CADANGAN');
        $crud->display_as('POKOK_TRANS','POKOK');
        $crud->display_as('KUITANSI','NO BUKTI');
        $crud->callback_column('POKOK_TRANS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('ADM',array($this,'_column_bonus_right_align'));
        $crud->callback_column('BUNGA_TRANS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('PAJAK_TRANS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('zakat_trans',array($this,'_column_bonus_right_align'));
        $crud->callback_column('TITIPAN_TRANS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('CADANGAN_TRANS',array($this,'_column_bonus_right_align'));
        $crud->callback_column('ADM',array($this,'_column_bonus_right_align'));
        $crud->callback_column('VERIFIKASI',array($this,'_column_center_align'));
        $crud->callback_column('TOB',array($this,'_column_center_align'));
        $crud->add_action('HAPUS', 'show_button', base_url().'admin/bo_deposito/browse_kretrans/kretrx_hps/?transid=');
        $crud->add_action('PEMBATALAN', 'show_button', '');
               
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Trx');
        
        $this->mTitle.= '[4008] Browse Daftar Transaksi Pembiayaan';
        $this->render_crud();
        
    }
    function _column_bonus_right_align($value)
    {       
        return "<span style=\"width:100%;text-align:right;display:block;\">".$this->rp($value)."</span>";
    }
    function _column_center_align($value,$row)
    {       
        return "<span style=\"width:100%;text-align:center;display:block;\">".$value."</span>";
    }
    function Rp($value)
    {
        return number_format($value,2,",",".");
    }
    function Rp1($value, $row = NULL)
    {
        return number_format($value,2,",",".");
    }
    
}
