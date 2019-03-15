<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Merchant
 *
 * @author edisite
 */
class Merchant extends API_Controller{
    //put your code here
    private $arr_lo = [
        'merchant_id'           => '',
        'merchant_norekening'   => '',
        'merchant_name'         => '',
        't_amount'              => '',
        't_notifikasi'          => '',
        't_trans_id'            => '',
        't_dtm_trans'           => '',
        't_dtm_expired'         => '',
        'status'                => '',
        'statusdesc'            => '',
        'systemdesc'            => ''
    ];
    
    public function __construct() {
        parent::__construct();
    }
    
    public function ListOrder_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_NO_REKENING         = $this->input->post('no_rekening') ?: '';      
        $in_USERID              = $this->input->post('m_userid') ?: '';
        if(empty($in_NO_REKENING))
        {
            $data = array('status' => '3002','message' => 'no_rekening invalid' , 'data' => array($this->arr_lo));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if(empty($in_USERID))
        {            
            $data = array('status' => '3001','message' => 'm_userid invalid' , 'data' => array($this->arr_lo));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_USERID)){            
        }else{
            $data = array('status' => '3001','message' => 'm_userid invalid' , 'data' => array($this->arr_lo));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
       
        $this->load->model(array('Merchant_model' => 'mc'));        
        $aa = $this->mc->ListOrder($in_USERID,$in_NO_REKENING);
        if($aa){
            foreach ($aa as $va) {
                $mc_id                     = $va->id;
                $mc_merchant_id            = $va->merchant_id;
                $mc_merchant_name          = $va->merchant_name;
                $mc_dtm_trans              = $va->dtm_insert;
                $mc_dtm_expired            = $va->dtm_expired;
                $mc_status                 = $va->status;
                $mc_keterangan             = $va->keterangan;
                $mc_transid                = $va->transid;
                
                if($mc_status == "0" || empty($mc_status))                {
                    $statustxt = "WAITING";
                }
                elseif ($mc_status == "2")                 {
                    $mc_status = '2';
                    $statustxt = "EXPIRED";                }
                elseif($mc_status == "1")                {
                    $statustxt = "SUCCES";
                }
                elseif($mc_status == "3")                {
                    $statustxt = "PROBLEM";
                }
                elseif($mc_status == "4")                {
                    $statustxt = "CANCEL";
                }
                else                {
                    $mc_status = '5';
                    $statustxt = "OTHERS";
                }
                //
                if($mc_status != '2') {  
                    $cstatus = $this->mc->MerchantStatusCheck($mc_id, $mc_dtm_expired);
                    if($cstatus)                    {   
                        
                    }else
                    {
                        $mc_status = '2';
                        $statustxt = "EXPIRED";
                        $mc_keterangan  = "expired, waktu konfirmasi oleh user telah habis";
                    }
                }
                $arres[] = array(
                        'merchant_id'           => $va->m_userid,
                        'merchant_norekening'   => $va->merchant_norekening,
                        'merchant_name'         => $va->merchant_name,
                        't_amount'              => $va->t_amount,
                        't_notifikasi'          => $va->t_notifikasi,
                        't_trans_id'            => $mc_transid,
                        't_dtm_trans'           => $mc_dtm_trans,
                        't_dtm_expired'         => $mc_dtm_expired,
                        'status'                => $mc_status,
                        'statusdesc'            => $statustxt,
                        'systemdesc'            => $mc_keterangan
                    );
                
            }
            $data = array('status' => '3000','msg' => 'OK');
            $data = array_merge($data, array('data' => $arres));
            $this->response($data);
            
        }else{
            $data = array('status' => '3003','msg' => 'List Order Kosong');
            $data = array_merge($data, array('data' => array($this->arr_lo)));
            $this->logAction('response', $trace_id, $data, 'failed, List Order Kosong');
            $this->response($data); 
        } 
    }
    public function ConfirmOrder_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_NO_REKENING         = $this->input->post('no_rekening') ?: '';      
        $in_USERID              = $this->input->post('m_userid') ?: '';
        $in_trans_id              = $this->input->post('trans_id') ?: '';
        if(empty($in_NO_REKENING))
        {
            $data = array('status' => '3002','message' => 'no_rekening invalid' , 'data' => array($this->arr_lo));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if(empty($in_USERID))
        {            
            $data = array('status' => '3001','message' => 'm_userid invalid' , 'data' => array($this->arr_lo));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if(empty($in_trans_id) || (strlen($in_trans_id) > 40 || strlen($in_trans_id) < 25))
        {            
            $data = array('status' => '102','msg' => 'Invalid trans_id');            
            $this->logAction('response', $trace_id, $data, 'failed, Invalid trans_id');
            $this->response($data);            
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_USERID))
        {}
        else{
            $data = array('status' => '3001','message' => 'm_userid invalid' , 'data' => array($this->arr_lo));
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        
        $this->load->model(array('Merchant_model' => 'mc'));        
        $aa = $this->mc->FindOrder($in_USERID, $in_NO_REKENING, $in_trans_id);
        //$this->response($aa);        
        if($aa){
            foreach ($aa as $va) {
                $mc_id                     = $va->id;
                $mc_merchant_id            = $va->merchant_id;
                $mc_merchant_norekening    = $va->merchant_norekening;
                $mc_merchant_name          = $va->merchant_name;
                $mc_dtm_trans              = $va->dtm_insert;
                $mc_dtm_expired            = $va->dtm_expired;
                $mc_status                 = $va->status;
                $mc_keterangan             = $va->keterangan;
                $mc_transid                = $va->transid;
                $mc_amount                = $va->t_amount;  
            }
            echo $this->StatusCheck($mc_id,$mc_status);
            return;
            
            if($this->StatusCheck($mc_id, $mc_status) != 0){
                $data = array('status' => '3004','msg' => 'Transaksi tidak bisa di proses');
                $data = array_merge($data, array('data' => array($this->arr_lo)));
                $this->logAction('response', $trace_id, $data, 'failed, List Order Kosong');
                $this->response($data); 
            }
            
                
            $data = array('status' => '3000','msg' => 'OK');
            $data = array_merge($data, array('data' => $arres));
            $this->response($data);
            
        }else{
            $data = array('status' => '3003','msg' => 'List Order Kosong');
            $data = array_merge($data, array('data' => array($this->arr_lo)));
            $this->logAction('response', $trace_id, $data, 'failed, List Order Kosong');
            $this->response($data); 
        } 
    }
    function StatusCheck($mc_id, $mc_status) {
        if($mc_status == "0" || empty($mc_status))                {
                    $mc_status = '2';
                    $statustxt = "WAITING";
                }            //
        if($mc_status != '2') {  
            $cstatus = $this->mc->MerchantStatusCheck($mc_id, $mc_dtm_expired);
            if($cstatus){                          
            }
            else
            {
                $mc_status = '2';                    
            }
        }
        return $mc_status;
    }
    
    public function GenQRCODE_post() {
        $norek                  = trim($this->input->post('norekening')) ?: '';
        $in_USERID              = $this->input->post('m_userid') ?: '';
        if(empty($in_USERID))
        {            
            $data = array('status' => '101','message' => 'm_userid invalid' , 'urlqrcode' => '');
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_USERID)){            
        }else{
            $data = array('status' => '101','message' => 'm_userid invalid' , 'urlqrcode' => '');
            $this->logAction('response', $trace_id, $data, 'failed');
            $this->response($data);
        }
        if(empty($norek)){
            $data = array('error_code' => '102','message' => 'norekening invalid','urlqrcode' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$norek.')');
            $this->response($data);
        } 
        
        $res_cek_rek = $this->Tab_model->Tab_nas($norek);
        if(!$res_cek_rek){            
            $data = array('error_code' => '102','message' => 'norekening invalid','urlqrcode' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$norek.')');
            $this->response($data);
        }
        
        $this->load->library('ciqrcode'); 
        $filename = 'dlqrcode/'.trim($norek).'.png';
        if(file_exists(FCPATH.$filename)){
            unlink(FCPATH.$filename);
        }
        
       // header("Content-Type: image/png");
        $config['cacheable']	= true; //boolean, the default is true
        $config['cachedir']		= ''; //string, the default is application/cache/
        $config['errorlog']		= ''; //string, the default is application/logs/
        $config['quality']		= true; //boolean, the default is true
        $config['size']			= ''; //interger, the default is 1024
        $config['black']		= array(224,255,255); // array, default is array(255,255,255)
        $config['white']		= array(70,130,180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);
        $params['data'] = trim($norek);
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH.$filename;
        
        $this->ciqrcode->generate($params);
        if(file_exists(FCPATH.$filename)){
            $data = array('error_code' => '100','message' => 'OK','urlqrcode' => base_url().$filename);
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$norek.')');
            $this->response($data);
        }else{
            $data = array('error_code' => '103','message' => 'Error generate qrcode','urlqrcode' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$norek.')');
            $this->response($data);
        }
    }
}
