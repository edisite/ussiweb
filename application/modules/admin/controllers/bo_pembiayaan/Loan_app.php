<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loan_app
 *
 * @author edisite
 */
class Loan_app extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('kre_pengajuan_e');       
         $crud->set_theme('datatables');
        $crud->columns('no_rekening','nama','nominal_pinjaman','tgl_pengajuan','keterangan','status');
        $crud->display_as('NAMA','nama_nasabah');
        $crud->callback_column('nominal_pinjaman',array($this,'_column_bonus_right_align'));
        $crud->callback_column('jumlah_angsuran',array($this,'_column_bonus_right_align'));
//        $crud->add_action('HAPUS', 'show_button', base_url().'admin/bo_simpanan/browse_tabtrans/tabtrx_hps/?idtrans=');
//        $crud->add_action('PEMBATALAN', 'show_button', '');
//               
//        $crud->unset_add();
//        $crud->unset_edit();
//        $crud->unset_delete();
//        $crud->unset_export();
//        $crud->unset_print();
        $crud->set_subject('');
        
        $this->mTitle.= '[2005]Pengajuan Pinjaman';
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
