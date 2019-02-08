<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Browse_tabtrans
 *
 * @author edisite
 */
class Browse_tabtrans extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $crud = $this->generate_crud('tabtrans');       
        $crud->set_model('Tabung_nasabah_model');
        $crud->columns('TABTRANS_ID','TGL_TRANS','NO_REKENING','nama_nasabah','MY_KODE_TRANS','KODE_TRANS','POKOK','ADM',
                'KUITANSI','KODE_KANTOR','USERID','KETERANGAN','VERIFIKASI','MODUL_ID_SOURCE','TRANS_ID_SOURCE',
                'TOB','KODE_PERK_OB','NO_REKENING_VS','POST_TO_GL','INTEGRASI','KODE_PRODUK','SANDI_TRANS');
        $crud->display_as('NAMA','nama_nasabah');
        $crud->callback_column('POKOK',array($this,'_column_bonus_right_align'));
        $crud->callback_column('ADM',array($this,'_column_bonus_right_align'));
        $crud->callback_column('VERIFIKASI',array($this,'_column_center_align'));
        $crud->callback_column('TOB',array($this,'_column_center_align'));
        $crud->add_action('HAPUS', 'show_button', base_url().'admin/bo_simpanan/browse_tabtrans/tabtrx_hps/?idtrans=');
        $crud->add_action('PEMBATALAN', 'show_button', '');
               
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_export();
        $crud->unset_print();
        $crud->set_subject('Trx');
        
        $this->mTitle.= '[2005]Browse Daftar Transaksi Simpanan';
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
    public function Tabtrx_hps() {
        $in_kd_trans = $this->input->get('idtrans');
        if(empty($in_kd_trans)){
            redirect();
        }        
        $res_common = $this->Tab_model->Tab_by_common($in_kd_trans);        
        foreach ($res_common as $subqry) {
            $in_common          = $subqry->COMMON_ID;
            $in_no_rek          = $subqry->NO_REKENING;
            $in_modul_id_source = $subqry->MODUL_ID_SOURCE;
            $in_trans_id_source = $subqry->TRANS_ID_SOURCE;
        }        
        if (!isset($in_common)){
            $in_common = 0;
        }
        if (!isset($in_no_rek)){
            redirect('admin/bo_simpanan/browse_tabtrans/?e=error1');
        }        
        $res_common = $this->Tab_model->Tab_by_jml_common($in_common);
        
        
        //delete
        $this->Tab_model->Del_tabtrans($in_kd_trans);
        
        $res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_no_rek);
        if(!$res_sum12){
            return "Error =====> ".$res_sum12;
        }
        foreach ($res_sum12 as $sub12) {
            $setoran12              = $sub12->SETORAN;
            $penarikan12            = $sub12->PENARIKAN;
            $setoran_bunga12        = $sub12->SETORAN_BUNGA;
            $penarikan_bunga12      = $sub12->PENARIKAN_BUNGA;
        }                        

        $hasil      = $setoran12 - $penarikan12;
        $bunga      = $setoran_bunga12 - $penarikan_bunga12;

        $dataupd = array(
            'SALDO_AKHIR'               => $hasil,
            'STATUS'                    => '1',
            'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga
        );
        
        $res_upd_tabung = $this->Tab_model->Upd_tabung($in_no_rek,$dataupd);
        if(!$res_upd_tabung){
            echo "Error : Gagal update database tabung";
            return;
        }
        $datadel = array(
            'TRANS_ID_SOURCE'               => $in_kd_trans
            );
        $this->Trans->Del_trans_master_by_transid_source($datadel);
        if($res_common > 0){
            $datadel_source = array(
                'MODUL_ID_SOURCE'               => $in_modul_id_source,
                'TRANS_ID_SOURCE'               => $in_trans_id_source
            );
            $this->Trans->Del_trans_master_by_transid_source($datadel_source);
            $res_modul = $this->Tab_model->Tab_by_modul_source($in_kd_trans);
            $this->Tab_model->Del_tabtrans($res_modul);
        }
        redirect('admin/bo_simpanan/browse_tabtrans');        
    }
}
