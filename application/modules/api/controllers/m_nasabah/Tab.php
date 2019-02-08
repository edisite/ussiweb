<?php
//3
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tab
 *
 * @author edisite
 */
class Tab extends API_Controller {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Saldo_post() {    
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_NO_REKENING         = $this->input->post('no_rekening') ?: '';      
        $in_USERID              = $this->input->post('m_userid') ?: '';
        if(empty($in_NO_REKENING))
        {
            $data = array('status' => '3002','message' => 'no_rekening isempty' , 'saldo' => '');
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if(empty($in_USERID))
        {            
            $data = array('status' => '3003','message' => 'm_userid isempty' , 'saldo' => '');
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_USERID)){            
        }else{
            $data = array('status' => '3004','message' => 'm_userid tidak terdaftar' , 'saldo' => '');
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        //$this->logAction('update ', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
        $res_ceksaldo =  $this->Tab_model->Tab_nas($in_NO_REKENING);
        if($res_ceksaldo){
            foreach ($res_ceksaldo as $val) {
                $saldo_akhir = $val->saldo_akhir;                
            }
        }else{
            $saldo_akhir = "0";
        }
        //$this->response($arr_res);
            $data = array('status' => '3000','message' => 'sukses' , 'saldo' => $this->Rp($saldo_akhir));
            $this->logAction('response', $trace_id, $data, 'OK');
            $this->response($data);
    }
    function Rp($value)
    {
        return number_format($value,2,",",".");
    }
    public function Mutasi_post() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        
        $no_rekening                = $this->input->post('no_rekening') ?: '';
        $in_periode                 = $this->input->post('periode') ?: '';
        $in_agentid                 = $this->input->post('m_userid') ?: '';
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
        if($this->Admin_user2_model->User_by_username_nasabah($in_agentid)){            
        }else{
            $data = array('status' => FALSE,'errorcode' => '514','message' => 'muserid invalid','riwayat'=>$par_history);
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
                $data = array('status' => TRUE,'errorcode' => '500','message' => 'success','riwayat'=>$par_history_success);
                $this->logAction('response', $trace_id, $data, 'success');
            
        }else{
            $data = array('status' => FALSE,'errorcode' => '501','message' => 'Riwayat isempty','riwayat'=>$par_history);
            $this->logAction('response', $trace_id, $data, 'data not found');
        }        
        $this->response($data);
    }
}
