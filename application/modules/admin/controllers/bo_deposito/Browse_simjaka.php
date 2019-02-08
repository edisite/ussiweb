<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Browse_simjaka
 *
 * @author edisite
 */
class Browse_simjaka extends Admin_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('deptrans');   
        //$crud->set_theme('datatables');
        $crud->set_model('Deptrans_nasabah_model');
        $crud->columns('DEPTRANS_ID','TGL_TRANS','KODE_TRANS','KETERANGAN','NO_REKENING','NAMA_NASABAH',
                'MY_KODE_TRANS','POKOK_TRANS','BUNGA_TRANS','PAJAK_TRANS','zakat_trans','TITIPAN_TRANS',
                'KUITANSI','KODE_KANTOR','USERID','VERIFIKASI','KODE_PERK_OB','NO_REKENING_TABUNGAN','CADANGAN_TRANS','POSTED_TO_GL');

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
        $crud->add_action('HAPUS', 'show_button', base_url().'admin/bo_deposito/browse_simjaka/deptrx_hps/?transid=');
        $crud->add_action('PEMBATALAN', 'show_button', '');
               
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Trx');
        
        $this->mTitle.= '[3005]Browse Daftar Transaksi Simpanan Berjangka';
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
    
    public function Deptrx_hps() {
        $in_kd_trans = $this->input->get('transid');
        if(empty($in_kd_trans)){
            redirect();
        }
        $res_norek = $this->Dep_model->Dep_by_transid($in_kd_trans);        
        if(empty($res_norek)){
            echo "error : No rekening is null";
            return;
        }
        $datadel_source = array(
            'MODUL_ID_SOURCE'               => 'DEP',
            'TRANS_ID_SOURCE'               => $in_kd_trans
            );
        $res_del_deptrans_by_source = $this->Trans->Del('DEPTRANS',$datadel_source);
        if(!$res_del_deptrans_by_source){
            return;
        }
        $datadel_id = array(
            'DEPTRANS_ID'               => $in_kd_trans
            );
        $res_del_deptrans_by_id = $this->Trans->Del('DEPTRANS',$datadel_id);
        if(!$res_del_deptrans_by_id){
            return;
        }
        
        $res_call_data = $this->Trans->Deb_trans_by_rek($res_norek);
        if(!$res_call_data){
            echo "Error : Data debtrans is null";
            return;         
        }
        foreach ($res_call_data as $sub_call_data) {
                $SALDO_AKHIR_POKOK      = $sub_call_data->SALDO_AKHIR_POKOK; 
                $SALDO_AKHIR_BUNGA      = $sub_call_data->SALDO_AKHIR_BUNGA; 
                $SALDO_AKHIR_PAJAK      = $sub_call_data->SALDO_AKHIR_PAJAK; 
                $SALDO_AKHIR_TITIPAN    = $sub_call_data->SALDO_AKHIR_TITIPAN; 
                $SALDO_AKHIR_ZAKAT      = $sub_call_data->SALDO_AKHIR_ZAKAT; 
                $SALDO_AKHIR_CADANGAN   = $sub_call_data->SALDO_AKHIR_CADANGAN; 
            }   
        
        $arr_upd = array(
            'SALDO_AKHIR_POKOK'     => $SALDO_AKHIR_POKOK,
            'SALDO_AKHIR_BUNGA'     => $SALDO_AKHIR_BUNGA,
            'SALDO_AKHIR_PAJAK'     => $SALDO_AKHIR_PAJAK,
            'SALDO_AKHIR_TITIPAN'   => $SALDO_AKHIR_TITIPAN,
            'SALDO_AKHIR_ZAKAT'     => $SALDO_AKHIR_ZAKAT,
            'SALDO_AKHIR_CADANGAN'  => $SALDO_AKHIR_CADANGAN,
            'STATUS_AKTIF'          => '1'
        );
        $res_call_upd = $this->Dep_model->Upd_deposito($res_norek,$arr_upd);
        if(!$res_call_upd){
            echo "error : Gagal update deposito";
            return;
        }
        $datadel_masterid = array(
            'TRANS_ID_SOURCE'               => $in_kd_trans
            );
        $res_del_deptrans_by_masterid = $this->Trans->Del('TRANSAKSI_MASTER',$datadel_masterid);
        if(!$res_del_deptrans_by_masterid){
            echo "error : Gagal delete masterid";
            return;
        }
        redirect(base_url().'admin/bo_deposito/browse_simjaka');
    }
}
