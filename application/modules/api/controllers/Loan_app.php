<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pengajuan_pinjaman
 *
 * @author edisite
 */
class Loan_app extends API_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        
    }
    public function Cekdata_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $in_agentid             = $this->input->post('agentid');
        $in_norekening          = $this->input->post('no_rekening');
        $in_nominal             = $this->input->post('nominal');
        $in_lama_pinjaman       = $this->input->post('lama_angsuran');
        $in_jumlah_angsuran     = $this->input->post('jumlah_angsuran');
        $in_jaminan             = $this->input->post('jaminan');
        
        if(empty($in_agentid)){
            
            $data = array(
                "status"       => FALSE,
                "error_code"   => '611',
                "message"      => 'agentid isempty'
            );
            $this->logAction('response', $trace_id, $data, 'failed, agentid is empty');
            $this->response($res_loan);            
        }
        if(empty($in_norekening)){
            $data = array(
                "status"       => FALSE,
                "error_code"   => '615',
                "message"      => 'no_rekening isempty'
            );
            $this->logAction('response', $trace_id, $data, 'failed, norekeing is empty');
            $this->response($data);
        }
        if(empty($in_nominal)){
            $data = array(
                "status"       => FALSE,
                "error_code"   => '616',
                "message"      => 'nominal isempty'
            );
            $this->logAction('response', $trace_id, $data, 'failed, nominal is empty');
            $this->response($data);
            
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array(
                "status"       => FALSE,
                "error_code"   => '612',
                "message"      => 'agentid invalid'
            );
            $this->logAction('response', $trace_id, $data, 'failed, agentid not found in database');
            $this->response($data);
        }
        $res_data_nasabah = $this->Tab_model->Tab_nas($in_norekening);
        $this->logAction('select', $trace_id, array(), 'Tab_model->Tab_nas('.$in_norekening.')');
        if($res_data_nasabah){
            foreach ($res_data_nasabah as $sub_data_nasabah) {
                $in_nasabahid           = $sub_data_nasabah->nasabah_id;
                $in_no_rekening         = $sub_data_nasabah->no_rekening;
                $in_nama_nasabah        = $sub_data_nasabah->nama_nasabah;
                $in_alamat              = $sub_data_nasabah->alamat;                
            }
            $this->logAction('result', $trace_id, $res_data_nasabah, 'success');
        }else{
            $data = array(
                "status"       => FALSE,
                "error_code"   => '613',
                "message"      => 'no_rekening invalid'
            );
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }        
        $ins_pengajuan = array(
            "nasabah_id"        => $in_nasabahid,
            "no_rekening"       => $in_no_rekening,
            "nama"              => $in_nama_nasabah,
            "alamat"            => $in_alamat,
            "nominal_pinjaman"  => $in_nominal,
            "lama_pinjaman"     => $in_lama_pinjaman,
            "jumlah_angsuran"   => $in_jumlah_angsuran,
            "tgl_pengajuan"     => $this->_Tgl_hari_ini(),
            "agent_id"          => $in_agentid,
            "status"            => "On Process",
            "keterangan"        => $in_jaminan
        );
        
        $res_ins_data = $this->Tab_model->Ins_Tbl('kre_pengajuan_e',$ins_pengajuan);
        $this->logAction('insert', $trace_id, $ins_pengajuan, 'kre_pengajuan_e');
        if($res_ins_data){
            
            $data = array(
                "status"       => TRUE,
                "error_code"   => '600',
                "message"      => 'success'
            );
            $this->logAction('response', $trace_id, $data, 'Success');
            $this->response($data);
        }else{
                
            $data = array(
                "status"       => FALSE,
                "error_code"   => '614',
                "message"      => 'Error Internal'
            );
            $this->logAction('result', $trace_id, $data, 'failed, insert insert tabel');        
            $this->response($data);
        }        
    }
    protected function _Tores($status = '',$error_code = '', $msg = '') {
        if(empty($status)){   return FALSE; }
        if(empty($error_code)){   return FALSE; }
        $res_loan = array(
            "status"       => $status,
            "error_code"   => $error_code,
            "message"      => $msg,
        );
        $this->response($res_loan);
        return;
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d H:i:s');
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
}
