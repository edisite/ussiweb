<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nasabah
 *
 * @author edisite
 */
class Nasabah extends API_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
         //$this->load->model('Tab_model');
    }
    public function Cek_get() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_nomor_rekening = $this->input->get('rekid');       
        $result_data_nasabah = $this->Tab_model->Tab_pro_nas_join($in_nomor_rekening);
        //$result_sandi = $this->Tab_model->Sandi_trans();  
        
        $this->logAction('select', $trace_id, array(), '$this->Tab_model->Tab_pro_nas_join('.$in_nomor_rekening.')');        
        
        $arr = array();
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $arr['no_rekening']         = $sub_res->NO_REKENING;
                $arr['id_nasabah']          = $sub_res->NASABAH_ID;
                $arr['nama_nasabah']        = $sub_res->nama_nasabah;
                $arr['alamat']              = $sub_res->alamat;
                $arr['produk']              = $sub_res->DESKRIPSI_PRODUK;
                $arr['tgl_reg']             = $sub_res->TGL_REGISTER;
                $arr['tgl_jt']              = $sub_res->TGL_JT;
                $arr['no_alt']              = $sub_res->NO_ALTERNATIF;
                $arr['saldo']               = $sub_res->SALDO_AKHIR;             
            }
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($arr);
        }
        else{
            $data = array('status' => FALSE,'error' => 'No Rekening Tidak di temukan');
            $this->logAction('response', $trace_id, $data, 'failed, data is empty in db');
            $this->response($data);
        }
        
        //print_r($result_data_nasabah);
    }
    public function Nasabah_all_get() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $result_data_nasabah = $this->Tab_model->Tab_nas_all();
        $this->logAction('select', $trace_id, array(), 'Tab_model->Tab_nas_all()');
        if($result_data_nasabah){
            $data = array('status' => true,'error' => '','data' => $result_data_nasabah);
            $this->logAction('response', $trace_id, array(), 'success');
            $this->response($data);
        }else{
            $data = array('status' => FALSE,'error' => 'Not found', 'data' => '');
            $this->logAction('response', $trace_id, $data, 'failed, data is empty');
            $this->response($data);
        }
    }
    public function Cek_rek_nasabah($in_nomor_rekening = null) {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $result_data_nasabah = $this->Tab_model->Tab_pro_nas_join($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Tab_model->Tab_pro_nas_join('.$in_nomor_rekening.')');
        //$result_sandi = $this->Tab_model->Sandi_trans();  
        $arr = array();
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $arr['no_rekening']         = $sub_res->NO_REKENING;
                $arr['id_nasabah']          = $sub_res->NASABAH_ID;
                $arr['nama_nasabah']        = $sub_res->nama_nasabah;
                $arr['alamat']              = $sub_res->alamat;
                $arr['produk']              = $sub_res->DESKRIPSI_PRODUK;
                $arr['tgl_reg']             = $sub_res->TGL_REGISTER;
                $arr['tgl_jt']              = $sub_res->TGL_JT;
                $arr['no_alt']              = $sub_res->NO_ALTERNATIF;
                $arr['saldo']               = $sub_res->SALDO_AKHIR;             
            }
            $this->logAction('response', $trace_id, $arr, 'success');
            //$this->response($arr);
            return json_encode($arr,TRUE);   
        }
        else{
            $data = array('status' => FALSE,'error' => 'No Rekening tidak ada');
            $this->logAction('response', $trace_id, $data, 'failed, data is empty');
            return json_encode($data,TRUE); 
            //return;
        }
        
        //print_r($result_data_nasabah);
    }
    public function Tab_find_by_kwd_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $get_data = $this->input->post('keyword');
        $get_agentid = $this->input->post('agentid');
        if($get_agentid == ""){
            $data = array('status' => FALSE,'error' => 'agentid','data' => NULL);
            $this->logAction('response', $trace_id, $data, 'agent is empty');
            $this->response($data); 
        }
        if($get_data == "" || strlen($get_data) >= 15 ){
            $data = array('status' => FALSE,'error' => 'keyword','data' => NULL);
            $this->logAction('response', $trace_id, $data, 'keyword empty or to long');
            $this->response($data); 
        }
        $res_call_data_nasabah = $this->Nas_model->Tab_src_nas($get_data);
        $this->logAction('select', $trace_id, array(), 'Nas_model->Tab_src_nas('.$get_data.')');
        if($res_call_data_nasabah){
            $data1 = array();
            foreach ($res_call_data_nasabah as $sub_res_data) {
                $data1[] = array(
                        "rekening" => $sub_res_data->rekening,
                        "nama" => $sub_res_data->nama,
                        "alamat" => '['.$sub_res_data->rekening.'] '.$sub_res_data->alamat,
                );
            }
            //echo json_encode($data);
            $data = array('status' => TRUE,'error' => '','data'=> $data1);
            $this->logAction('response', $trace_id, $data1, 'success');
            $this->response($data);
        }else{
            $data = array('status' => FALSE,'error' => 'data','data'=> NULL);
            $this->logAction('response', $trace_id, $data, 'failed, data is empty');
            $this->response($data); 
        }
       
    }
    public function Dep_find_by_kwd_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $get_data = $this->input->post('keyword');
        $get_agentid = $this->input->post('agentid');
        if($get_agentid == ""){
            $data = array('status' => FALSE,'error' => 'agentid','data' => NULL);
            $this->logAction('response', $trace_id, $data, 'agentid is empty');
            $this->response($data); 
        }
        if($get_data == "" || strlen($get_data) >= 15 ){
            $data = array('status' => FALSE,'error' => 'keyword','data' => NULL);
            $this->logAction('response', $trace_id, $data, 'agentid lenght ['.  strlen($get_data).']');
            $this->response($data); 
        }
        $res_call_data_nasabah = $this->Nas_model->Dep_src_nas($get_data);
        $this->logAction('select', $trace_id, array(), 'Nas_model->Dep_src_nas('.$get_data.')');
        if($res_call_data_nasabah){
            $data1 = array();
            foreach ($res_call_data_nasabah as $sub_res_data) {
                $data1[] = array(
                        "rekening" => $sub_res_data->rekening,
                        "nama" => $sub_res_data->nama,
                        "alamat" => $sub_res_data->alamat,
                );
            }
            //echo json_encode($data);
            $data = array('status' => TRUE,'error' => '','data'=> $data1);
            $this->logAction('response', $trace_id, $data1, 'success');
            $this->response($data);
        }else{
            $data = array('status' => FALSE,'error' => 'data','data'=> NULL);
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data); 
        }
       
    }
    public function Kre_find_by_kwd_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $get_data = $this->input->post('keyword');
        $get_agentid = $this->input->post('agentid');
        if($get_agentid == ""){
            $data = array('status' => FALSE,'error' => 'agentid','data' => NULL);
            $this->logAction('response', $trace_id, $data, 'agentid is empty');
            $this->response($data); 
        }
        if($get_data == "" || strlen($get_data) >= 15 ){
            $data = array('status' => FALSE,'error' => 'keyword','data' => NULL);
            $this->logAction('response', $trace_id, $data, 'agentid lenght ['.  strlen($get_data).']');
            $this->response($data); 
        }
        $res_call_data_nasabah = $this->Nas_model->Kre_src_nas($get_data);
        $this->logAction('select', $trace_id, array(), 'Nas_model->Kre_src_nas('.$get_data.')');
        if($res_call_data_nasabah){
            $data1 = array();
            foreach ($res_call_data_nasabah as $sub_res_data) {
                $data1[] = array(
                        "rekening" => $sub_res_data->rekening,
                        "nama" => $sub_res_data->nama,
                        "alamat" => '['.$sub_res_data->rekening.']['.$this->Rp($sub_res_data->jml_pinjaman).'] - '.$sub_res_data->alamat,
                );
            }
            //echo json_encode($data);
            $data = array('status' => TRUE,'error' => '','data'=> $data1);
            $this->logAction('response', $trace_id, $data1, 'success');
            $this->response($data);
        }else{
            $data = array('status' => FALSE,'error' => 'data','data'=> NULL);
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data); 
        }       
    }   
    protected function Rp($value)    {
        return number_format($value,0,",",".");
    }
    
}
