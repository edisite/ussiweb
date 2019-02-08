<?php

// errorcode 500

define('setor_my_kode_trans', '100');
define('setor_kode_trans', '100');
define('tarik_my_kode_trans', '200');
define('tarik_kode_trans', '200');
define('setor_sandi', '01');
define('tarik_sandi', '02');

define('KODE_KANTOR_DEFAULT', '35');
define('ADM_DEFAULT', '0.00');
define('KODEPERKKAS_DEFAULT', '10101');
define('veririfikasi', '1');
define('kode_kolektor', '000');
define('kode_kantor_default', '35');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tabung
 *
 * @author edisite
 */
class Tabung extends API_Controller{
    //put your code here
    
    private $tabsaldo_awal,$cek_saldo_akhir  = 0;
    
    private $subresdata = array('saldo_awal' => '','nominal' => '','saldo_akhir' => '');
                
    public function __construct() {
        parent::__construct();
        //$this->load->library('Dblib');
        
    }
    public function Mutasi_post() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        
        $no_rekening                = $this->input->post('no_rekening');
        $in_periode                 = $this->input->post('periode');
        $in_agentid                 = $this->input->post('agentid');
        $par_history = array();
        $par_history[] = array(
                    "tgl"   => '',
                    "sandi"   => '',
                    "setoran"   => '',
                    "penarikan"   => '',
                    "saldo"   => '',
                    "keterangan"   => '',
                    "kolektor"   => ''
                );
        if(empty($in_agentid)){
            $data = array('status' => FALSE,'errorcode' => '511','message' => 'agentid isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'agent is empty');
            $this->response($data);
        }
        if(empty($no_rekening)){
            $data = array('status' => FALSE,'errorcode' => '512','message' => 'no_rekening isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'rekening is empty');
            $this->response($data);
        }
        if(empty($in_periode)){
            $data = array('status' => FALSE,'errorcode' => '513','message' => 'periode isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'periode is empty');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array('status' => FALSE,'errorcode' => '514','message' => 'agentid invalid','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'agent not found in databases');
            $this->response($data);
        }
        
        $res_call_saldo_awal = $this->Tab_model->lap_mutasi_periode_saldo_awal($no_rekening,$in_periode);
        $this->logAction('response', $trace_id, array(), 'Tab_model->lap_mutasi_periode_saldo_awal('.$no_rekening.','.$in_periode.')');
        if(!$res_call_saldo_awal){
            $data = array('status' => FALSE,'errorcode' => '501','message' => 'Riwayat isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'data not found');
            $this->response($data);
        }
        $this->logAction('response', $trace_id, $res_call_saldo_awal, 'get saldo awal');
        foreach ($res_call_saldo_awal as $val_awal){
            $get_saldo_awal = $val_awal->saldo_awal ?: 0;
        }
        $res_call_history = $this->Tab_model->lap_mutasi_periode($no_rekening,$in_periode,$get_saldo_awal);
        $this->logAction('select', $trace_id, array(), 'Tab_model->Lap_mutasi_periode('.$no_rekening.','.$in_periode.','.$get_saldo_awal.')');
        if(!$res_call_history){
            $data = array('status' => FALSE,'errorcode' => '501','message' => 'Riwayat isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'data not found');
            $this->response($data);
        }    
        
//        $res_call_history = $this->Trans->Tabtrans_tab_nas_by_rek($no_rekening,$in_periode);   
        $this->logAction('result', $trace_id, $res_call_history, '');
        if($res_call_history){
            $par_history_success = array();
            foreach ($res_call_history as $val_history) {                
                
                $par_history_success[] = array(
                    "tgl"           => $val_history->Tanggal,
                    "sandi"         => $val_history->KODE_TRANS,
                    "setoran"       => $this->Rp($val_history->Debit),
                    "penarikan"     => $this->Rp($val_history->Kredit),
                    "saldo"         => $this->Rp($val_history->SALDO),
                    "keterangan"    => $val_history->KETERANGAN,
                    "kolektor"      => $val_history->kode_kolektor
                );
                
            }
            /*if(empty($par_history_success)){
                $data = array('status' => FALSE,'errorcode' => '501','message' => 'Riwayat isempty','riwayat'=>$par_history);
                $this->logAction('response', $trace_id, $data, 'data not found');
            }else{*/
                $data = array('status' => TRUE,'errorcode' => '500','message' => 'success','riwayat'=>$par_history_success);
                $this->logAction('response', $trace_id, $data, 'success');
            //}
            
        }else{
            $data = array('status' => FALSE,'errorcode' => '501','message' => 'Riwayat isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'data not found');
        } 
        $this->Logend($trace_id);
        $this->response($data);
        
    }
    public function Sandi_get() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        $arr = array();
        $result_sandi = $this->Tab_model->Sandi_trans();  
        $this->logAction('select', $trace_id, array(), 'Tab_model->Sandi_trans()');
	if($result_sandi){
            $this->logAction('response', $trace_id, $result_sandi, 'success');
            $this->response($result_sandi);
        }else{
            $data = array('status' => FALSE,'error' => 'Sandi Tidak di temukan');
             $this->logAction('response', $trace_id, $data, 'failed, data nt found in db');
            $this->response($data);
        } 
        $this->Logend($trace_id);
    }
    public function Kode_trans_get() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        $arr = array();
        $result_sandi = $this->Tab_model->Kodetrans_filter();  
        $this->logAction('select', $trace_id, array(), 'Tab_model->Kodetrans_filter()');
	if($result_sandi){
            $this->logAction('response', $trace_id, $result_sandi, 'success');
            $this->response($result_sandi);
        }else{
            $data = array('status' => FALSE,'error' => 'kode_trans');
            $this->logAction('response', $trace_id, $data, 'failed, data nt found in db');
            $this->response($data);
        }   
        $this->Logend($trace_id);
    }
    protected function _Check_postdata() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $data = array('status' => FALSE,'error' => 'Request method failed');
            $this->response($data);
            return;
        }
        if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );               
        if(empty($HTTP_RAW_POST_DATA)){
            $data = array('status' => FALSE,'error' => 'Parameter not found');
            $this->response($data);
            return;
        }
    }

    public function Setor_post() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        $in_NO_REKENING         = $this->input->post('no_rekening') ?: '';
        $in_POKOK               = $this->input->post('nominal') ?: '';
        $in_adm                 = $this->input->post('adm') ?: '';       
        $in_USERID              = $this->input->post('agentid') ?: '';
        
        if(empty($in_USERID)){
            $data = array('status' => FALSE,'code' => '' ,'error' => 'agent','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'agent is empty');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_id($in_USERID)){            
        }else{
            $data = array('status' => FALSE,'code' => '' ,'error' => 'agent','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'agent not registered');
            $this->response($data);
        }
        if(empty($in_NO_REKENING)){
            $data = array('status' => FALSE,'code' => '' ,'error' => 'no_rekening','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'no rekening is empty');
            $this->response($data);
        }
        if(empty($in_POKOK) || !is_numeric($in_POKOK)){
            $data = array('status' => FALSE,'code' => '' ,'error' => 'nominal','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'nominal is empty');
            $this->response($data);
        }
        
        $in_kd_kantor           = kode_kantor_default;
        $in_adm_penutupan       = '';       
        $in_SANDI_TRANS         = setor_sandi;        
        $in_kd_trans            = setor_kode_trans;
        $in_mykdetrans          = setor_my_kode_trans;
        $in_ver                 = veririfikasi;
        $in_KODE_KOLEKTOR       = kode_kolektor;
        if($in_kd_trans == 100){//nabung
            $in_DEBET100   = $in_POKOK ;
            $in_KREDIT100  = 0 ;
            $in_DEBET200   = 0;
            $in_KREDIT200  = $in_POKOK ;
        }elseif($in_kd_trans == 200){ // tarikan
            $in_DEBET100   = 0 ;
            $in_KREDIT100  = $in_POKOK;
            $in_DEBET200   = $in_POKOK ;
            $in_KREDIT200  = 0;
        }else{
            $data = array('status' => FALSE,'code' => '' ,'error' => 'kode_trans','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'kode_trans is empty');
            $this->response($data);
            return;
        }

        $res_cek_rek = $this->Tab_model->Tab_nas($in_NO_REKENING);
        if(!$res_cek_rek){
            $data = array('status' => FALSE,'code' => '' ,'error' => 'no_rekening','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_NO_REKENING.')');
            $this->response($data);
            return;
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah       = $sub_cek_rek->nama_nasabah;
            $this->tabsaldo_awal     = $sub_cek_rek->saldo_akhir;
        }
        $gen_id_TABTRANS_ID         = $this->App_model->Gen_id();
        $gen_id_COMMON_ID           = $this->App_model->Gen_id();
        $gen_id_MASTER              = $this->App_model->Gen_id();
        $in_KUITANSI                = $this->_Kuitansi();
        $in_keterangan              = $this->_Kodetrans_by_desc($in_kd_trans);
        $in_tob                     = $this->_Kodetrans_by_tob($in_kd_trans);
        $in_keterangan              = $in_keterangan." : an ".$in_NO_REKENING." ".$nama_nasabah;
        $unitkerja                  = $in_kd_kantor;
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_NO_REKENING, 
            'MY_KODE_TRANS'     =>$in_mykdetrans, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$in_POKOK,
            'ADM'               =>$in_adm,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>$in_ver, 
            'USERID'            =>$in_USERID, 
            'KODE_TRANS'        =>$in_kd_trans,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>$in_SANDI_TRANS,
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>$in_KODE_KOLEKTOR,
            'KODE_KANTOR'       =>$unitkerja,
            'ADM_PENUTUPAN'     =>$in_adm_penutupan,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            
            $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
            $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl(TABTRANS)');
            if(!$res_ins){
                $data = array('status' => FALSE,'code' => '' ,'error' => 'error db','data' =>  $this->subresdata);
                $this->logAction('response', $trace_id, $data, 'failed, insert unsuccessful');
                $this->response($data);
                return;
            }
            $this->logAction('update ', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
            $upd_saldo = $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
            if(!$upd_saldo){
                $this->logAction('response', $trace_id, array(), 'failed');
            }

            $res_call_tab_trans = $this->Tab_model->Tab_trans($gen_id_TABTRANS_ID);
            $this->logAction('select', $trace_id, array(), 'Tab_model->Tab_trans('.$gen_id_TABTRANS_ID.')');
            if(!$res_call_tab_trans){
                $data = array('status' => FALSE,'code' => '' ,'error' => 'error db','data' =>  $this->subresdata);
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('response', $trace_id, $data, 'failed, update unsuccessful');
                $this->response($data);
                return;
            }                     

            $res_call_tab   = $this->Tab_model->Tab_byrek($in_NO_REKENING);
            $this->logAction('select', $trace_id, array(), 'kode_integrasi > Tab_model->Tab_byrek('.$in_NO_REKENING.')');
            if($res_call_tab){
                foreach($res_call_tab as $sub_call_tab){
                    $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
                }
            }else{
                $in_kode_integrasi = "";
            }
            $this->logAction('result', $trace_id, array('kode_integrasi' => $in_kode_integrasi), 'kode integrasi');
            
            $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_USERID);
            $this->logAction('select', $trace_id, array(), 'kode_perk_kas > Sys_daftar_user_model->Perk_kas('.$in_USERID.')');
            if($res_call_kode_perk_kas){
                foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                    $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                }
                $this->logAction('result', $trace_id, array('kode_perk_kas' => $in_kode_perk_kas), 'kode_perk_kas');
            } else {
                $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
                $this->logAction('result', $trace_id, array('kode_perk_kas' => $in_kode_perk_kas), 'kode_perk_kas set default');
            }

            $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            $this->logAction('select', $trace_id, array(), 'perkkode_G_OR_D > Perk_model->perk_by_kodeperk('.$in_kode_perk_kas.')');
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }else{
                $in_nama_perk = "";
                $in_gord            = "";
            }
            
            $this->logAction('result', $trace_id, array('G_OR_D' => $in_gord,'nama_perk' => $in_nama_perk), 'g_or_d');
            $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
            $this->logAction('select', $trace_id, array(), 'kode integrasi > Tab_model->Integrasi_by_kd_int('.$in_kode_integrasi.')');
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
            }
            $this->logAction('result', $trace_id, $res_call_tab_integrasi_by_kd, 'kode integrasi');
            $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
            
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total;
            }

            $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
            }
            $this->logAction('kodejurnal', $trace_id, array(), 'kode jurnal = '.$res_kode_jurnal);
            $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_USERID, 
                'KODE_KANTOR'       =>  $unitkerja
            );
            $ar_trans_detail = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                    'DEBET'         =>  $in_DEBET200, 
                    'KREDIT'        =>  $in_KREDIT200
                ),array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  $in_DEBET100, 
                    'KREDIT'        =>  $in_KREDIT100                                                             
                )
            );
            
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
            $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
            if($res_run){                
                $in_addpoin = array(
                    'tid'   => $gen_id_TABTRANS_ID,
                    'agent' => $in_USERID,
                    'kode'  => $in_kd_trans,
                    'jenis' => 'TAB',
                    'nilai' => $in_POKOK
                );
                $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
                $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
                $this->logAction('result', $trace_id, array(), 'response');
                $subresdata = array('saldo_awal' => $this->Rp($this->tabsaldo_awal),'nominal' => $this->Rp($in_POKOK),'saldo_akhir' => $this->Rp($this->_Saldo($in_NO_REKENING)));
                
                $data = array('status' => TRUE,'code' => $gen_id_TABTRANS_ID ,'message' => 'success','data' => $subresdata);
                $this->logAction('result', $trace_id, array(), 'success, running trasaction');
                $this->logAction('response', $trace_id, $data, '');
                $this->response($data);                
            }else{            
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $data = array('status' => FALSE,'error' => 'Gagal transaction','data' =>  $this->subresdata);
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('update ', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
                $upd_saldo = $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
                $this->logAction('response', $trace_id, $data, '');
                $this->Logend($trace_id);
                $this->response($data);                
            }             
    }    
    
    public function Tarik_post() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        $in_NO_REKENING         = $this->input->post('no_rekening');
        $in_POKOK               = $this->input->post('nominal');
        $in_adm                 = $this->input->post('adm');       
        $in_USERID              = $this->input->post('agentid');
        if(empty($in_USERID)){
            $data = array('status' => false,'code' => '' ,'error' => 'agentid','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'agentid is empty');
            $this->response($data);
            return;
        }
        if(empty($in_POKOK)){
            $data = array('status' => false,'code' => '' ,'error' => 'nominal','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'nominal is empty');
            $this->response($data);
            return;
        }
        if($this->Admin_user2_model->User_by_id($in_USERID)){            
        }else{
            $data = array('status' => FALSE,'code' => '' ,'error' => 'agent','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'agentid not registered');
            $this->response($data);
            return;
        }
        $in_kd_kantor           = kode_kantor_default;
        $in_adm_penutupan       = '';       
        $in_SANDI_TRANS         = setor_sandi;       
        $in_kd_trans            = tarik_kode_trans;
        $in_mykdetrans          = tarik_my_kode_trans;
        $in_ver                 = veririfikasi;
        $in_KODE_KOLEKTOR       = kode_kolektor;
        if($in_kd_trans == 100){//nabung
            $in_DEBET100   = $in_POKOK ;
            $in_KREDIT100  = 0 ;
            $in_DEBET200   = 0;
            $in_KREDIT200  = $in_POKOK ;
        }elseif($in_kd_trans == 200){ // tarikan
            $in_DEBET100   = 0 ;
            $in_KREDIT100  = $in_POKOK;
            $in_DEBET200   = $in_POKOK ;
            $in_KREDIT200  = 0;
        }else{
            $data = array('status' => FALSE,'code' => '' ,'error' => 'kode_trans','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'kode_trans is empty');
            $this->response($data);
            return;
        }
        $res_cek_rek = $this->Tab_model->Tab_nas($in_NO_REKENING);
        if(!$res_cek_rek){
            $data = array('status' => FALSE,'code' => '' ,'error' => 'no_rekening','data' =>  $this->subresdata);
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_NO_REKENING.')');
            $this->response($data);
            return;
        }
        
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $this->cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        //cek saldo 
        if($in_POKOK > round($this->cek_saldo_akhir)){
            $data = array('status' => FALSE,'code' => '' ,'error' => 'saldo_minus','data' =>  $this->subresdata);
            $this->response($data);
            return;
            
        }
        
        $gen_id_TABTRANS_ID = $this->App_model->Gen_id();
        $gen_id_COMMON_ID   = $this->App_model->Gen_id();
        $gen_id_MASTER      = $this->App_model->Gen_id();
        $in_KUITANSI        = $this->_Kuitansi();
        $in_keterangan      = $this->_Kodetrans_by_desc($in_kd_trans);
        $in_tob             = $this->_Kodetrans_by_tob($in_kd_trans);
        $in_keterangan      = $in_keterangan." : an ".$in_NO_REKENING." ".$nama_nasabah;
        $unitkerja          = $in_kd_kantor;
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_NO_REKENING, 
            'MY_KODE_TRANS'     =>$in_mykdetrans, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$in_POKOK,
            'ADM'               =>$in_adm,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>$in_ver, 
            'USERID'            =>$in_USERID, 
            'KODE_TRANS'        =>$in_kd_trans, 
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>$in_SANDI_TRANS,
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>$in_KODE_KOLEKTOR,
            'KODE_KANTOR'       =>$unitkerja,
            'ADM_PENUTUPAN'     =>$in_adm_penutupan,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            
            $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
            $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl(TABTRANS)');
            if(!$res_ins){
                $data = array('status' => FALSE,'code' => '' ,'error' => 'error_db','data' =>  $this->subresdata);
                $this->logAction('response', $trace_id, $data, 'failed, insert unsuccessful');
                $this->response($data);
                return;
            }
            $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
            $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);

            $res_call_tab   = $this->Tab_model->Tab_byrek($in_NO_REKENING);
            $this->logAction('select', $trace_id, array(), 'kode_integrasi > Tab_model->Tab_byrek('.$in_NO_REKENING.')');
            if($res_call_tab){
                foreach($res_call_tab as $sub_call_tab){
                    $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
                }
            }
            $this->logAction('result', $trace_id, array('kode_integrasi' => $in_kode_integrasi), 'kode integrasi');
            $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_USERID);
            $this->logAction('select', $trace_id, array(), 'kode_perk_kas > Sys_daftar_user_model->Perk_kas('.$in_USERID.')');
            if($res_call_kode_perk_kas){
                foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                    $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                }
                $this->logAction('result', $trace_id, array('kode_perk_kas' => $in_kode_perk_kas), 'kode_perk_kas');
            }
            if(empty($in_kode_perk_kas)){
                $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
            }
            
            
            $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            $this->logAction('select', $trace_id, array(), 'perkkode_G_OR_D > Perk_model->perk_by_kodeperk('.$in_kode_perk_kas.')');
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk   = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord        = $sub_call_perk_kode_gord->G_OR_D;
                }
            }else{
                $in_gord = "";
            }
            $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
            $this->logAction('select', $trace_id, array(), 'kode integrasi > Tab_model->Integrasi_by_kd_int('.$in_kode_integrasi.')');
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
            }
            $this->logAction('result', $trace_id, $res_call_tab_integrasi_by_kd, 'kode integrasi');
            $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total;
            }

            $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
            }
            $this->logAction('kodejurnal', $trace_id, array(), 'kode jurnal = '.$res_kode_jurnal);
            $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_USERID, 
                'KODE_KANTOR'       =>  $unitkerja
            );

            $ar_trans_detail = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                    'DEBET'         =>  $in_DEBET200, 
                    'KREDIT'        =>  $in_KREDIT200
                ),array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  $in_DEBET100, 
                    'KREDIT'        =>  $in_KREDIT100                                                             
                )
            );
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
            $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
            if($res_run){                
                $in_addpoin = array(
                    'tid'   => $gen_id_TABTRANS_ID,
                    'agent' => $in_USERID,
                    'kode'  => $in_kd_trans,
                    'jenis' => 'TAB',
                    'nilai' => $in_POKOK ?: 0
                );
                $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
                $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
                $this->logAction('result', $trace_id, array(), 'response');
                $subresdata = array('saldo_awal' => $this->Rp($this->cek_saldo_akhir),'nominal' => $this->Rp($in_POKOK),'saldo_akhir' => $this->Rp($this->_Saldo($in_NO_REKENING)));
                
                $data = array('status' => TRUE,'code' => $gen_id_TABTRANS_ID ,'message' => 'success','data' => $subresdata);
                $this->logAction('result', $trace_id, array(), 'success, running trasaction');
                $this->logAction('response', $trace_id, $data, '');
                $this->response($data);
                
            }else{            
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $data = array('status' => FALSE,'error' => 'Gagal transaction','data' =>  $this->subresdata);
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
                $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
                $this->logAction('response', $trace_id, $data, '');
                $this->response($data);
                
            }       
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    protected function _Kuitansi() {
        $result_kwitn = $this->Tab_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
            }
            if(empty($out_nokwi)){
                $out_nokwi = "Tab.1";
            }else{
                $out_nokwi = increment_string($out_nokwi,'.');
            }
            return $out_nokwi;
    }
    protected function _Kodetrans_by_desc($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Tab_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_desc          = $res_desc->deskripsi;
                }
        }else{
            $out_desc   = "";
        }
        return $out_desc;
    }
    protected function _Kodetrans_by_tob($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Tab_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_tob          = $res_desc->TOB;
                }
                return $out_tob;
        }else{
            if(setor_kode_trans == 100 || setor_kode_trans == 200){
                return 'T';
            }else{
                return 'O';
            }
        }
        return $out_tob;
    }
    public function Saldo_post() {
//        $this->_Check_postdata();        
//        if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );  
//        $res_call_post_data = json_decode($HTTP_RAW_POST_DATA,TRUE);
//        
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        
        $in_NO_REKENING         = $this->input->post('no_rekening');      
        $in_USERID              = $this->input->post('agentid');
        if(empty($in_NO_REKENING))
        {
            $this->response(array('status' => FALSE,'messase' => 'no_rekening'));
            $this->logAction('response', $trace_id, array(), 'no_rekening is invalid');
        }
        if(empty($in_USERID))
        {
            $this->response(array('status' => FALSE,'messase' => 'agentid'));
            $this->logAction('response', $trace_id, array(), 'agentid is invalid');
        }
        if($this->Admin_user2_model->User_by_id($in_USERID)){            
        }else{
            $data = array('status' => FALSE,'messase' => 'agent');
            $this->logAction('response', $trace_id, $data, 'agentid is invalid');
            $this->response($data);
        }
        //$this->logAction('update ', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
        $res_ceksaldo =  $this->Tab_model->Tab_nas($in_NO_REKENING);
        if($res_ceksaldo){
            foreach ($res_ceksaldo as $val) {
                $saldo_akhir = $val->saldo_akhir;                
            }
            $arr_res = array('status' => true,'saldo' => $this->Rp($saldo_akhir),'message' => 'success');
            $this->logAction('response', $trace_id, $arr_res, 'sukses:');
        }else{
            $saldo_akhir = 0;
            $arr_res = array('status' => false,'saldo' => $this->Rp($saldo_akhir),'message' => 'not success');
            $this->logAction('response', $trace_id, $arr_res, 'not success: ');            
        }
        $this->Logend($trace_id);
        $this->response($arr_res);   
    }
    function Rp($value)
    {
        return number_format($value,0,",",".");
    }
    function _Saldo($in_NO_REKENING) {
        $res_ceksaldo =  $this->Tab_model->Tab_nas($in_NO_REKENING);
        if($res_ceksaldo){
            foreach ($res_ceksaldo as $val) {
                $saldo_akhir = $val->saldo_akhir;                
            }
        }else{
            $saldo_akhir = 0;
        }
        return $saldo_akhir;
    }
}
