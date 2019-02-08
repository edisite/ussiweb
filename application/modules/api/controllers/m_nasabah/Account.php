<?php
//No Errorcode 2
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account
 *
 * @author edisite
 */
class Account extends API_Controller{
    //put your code here
    protected $rnasabahid, $rusername;
    protected $wres_data = array();
    public function __construct() {
        parent::__construct();
    }
    
    public function List_rekening_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_nasabahid   = $this->input->post('nasabahid') ?: '';
        $in_m_userid    = $this->input->post('m_userid') ?: '';
        if(empty($in_m_userid)){
            
            $data = array('status' => '2002','message' => 'userid nasabah isempty' , 'data' => array('no_rekening' => '','nasabahid' => ''));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if(empty($in_nasabahid)){
            $data = array('status' => '2003','message' => 'nasabah_id isempty','data' => array('no_rekening' => '','nasabahid' => ''));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        
        //cek userid 
        $get_nasabah = $this->Admin_user2_model->User_by_username_nasabah($in_m_userid);
        if($get_nasabah){
            foreach ($get_nasabah as $v) {
                //username,nasabah_id
                $rnasabahid     = $v->nasabah_id;
                $rusername      = $v->username;
            }
        }else{
            $data = array('status' => '2004','message' => 'user tidak terdaftar' , 'data' => array('no_rekening' => '','nasabahid' => ''));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        
        if(trim($in_nasabahid) == $rnasabahid){            
        }else{
            $data = array('status' => '2005','message' => 'nasabahid tidak terdaftar' , 'data' => array('no_rekening' => '','nasabahid' => ''));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        
        //get rekening
        $this->logAction('info', $trace_id, array(), 'get list rekening');
        $res_data_rekening = $this->Tab_model->Rek_by_nasabahid($rnasabahid);
        if($res_data_rekening){
            
            foreach ($res_data_rekening as $vrek) {
                $rrekening = $vrek->NO_REKENING;  
                $wres_data[] = array('no_rekening' => $rrekening,'nasabahid' => $rnasabahid);
            }
            
            $data = array('status' => '2000','message' => 'sukses' , 'data' => $wres_data);
            $this->logAction('response', $trace_id, $data, 'respon');
            $this->response($data);
        }else{
            $data = array('status' => '2005','message' => 'nasabahid tidak terdaftar' , 'data' => array('no_rekening' => '','nasabahid' => ''));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
    }
}
