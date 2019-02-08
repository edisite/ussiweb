<?php
//errocode 600
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Commerce
 *
 * @author edisite
 */
class Commerce extends API_Controller{
    //put your code here
    var $in_rkning;
    public function __construct() {
        parent::__construct();
    }
    public function Provider_list_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $in_provider = $this->input->post('category') ? $this->input->post('category') : '';
        if(empty($in_provider)){
            $data = array('status' => FALSE,
                'errorcode' => '611',
                'message' => 'category is empty',
                'list' => '');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }
        $res_list = $this->Pay_model->List_op(strtolower($in_provider));
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("'.$in_provider.'")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
//                if(is_numeric($val_list->product_alias))
//                {
//                    $alias = $this->Rp($val_list->product_alias);
//                }else{
//                    $alias = $val_list->product_alias;
//                }
                $arr[] = array(
                    'code'    => $val_list->code_provider,
                    'provider'  => $val_list->provider
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600' , 
                'message' => 'success' , 
                'list' => $arr);
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '612' , 
                'message' => 'data isempty' , 
                'list' => array('code' => '','provider' => ''));
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Product_list_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_code_operator = $this->input->post('code') ? $this->input->post('code') : '';
        
        if(empty($in_code_operator)){
            $data = array('status' => FALSE,
                'errorcode' => '611',
                'message' => 'code is empty',
                'list' => '');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }
        $res_list = $this->Pay_model->List_product($in_code_operator);
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa('.$in_code_operator.')');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
                if(is_numeric($val_list->product_alias))
                {
                    $alias = $this->Rp($val_list->product_alias);
                }else{
                    $alias = $val_list->product_alias;
                }
                $arr[] = array(
                    'provider'  => $val_list->provider,
                    'product_id'    => $val_list->product_id,
                    'product_name'    => $alias,                    
                    'price'     => $this->Rp($val_list->price_selling)
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600' , 
                'message' => 'success' , 
                'list' => $arr);
            $this->logAction('response', $trace_id, $data, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '612' , 
                'message' => 'data isempty' , 
                'list' => array(
                    'provider'  => '',
                    'product_id'    => '',
                    'product_name'    => '',                    
                    'price'     => ''
                ));
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Pln_postpaid_inq_post() {
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $no_meter = $this->input->post('meterid') ? $this->input->post('meterid') : '';
        $in_agenid = $this->input->post('agentid') ? $this->input->post('agentid') : '';
        
        $res_result = array(
            'meterid'       => '',
            'cust_name'     => '',
            'daya'          => '',
            'tagihan'       => '',
            'adm'           => '',            
            'total'         => '',            
            'code'          => ''            
        );
        $this->_Check_prm_data($no_meter,'611', $trace_id, 'meterid isempty',$res_result);
        $this->_Check_prm_data($no_meter,'613', $trace_id, 'agentid isempty',$res_result);
        if (!is_numeric($no_meter))	{
            $data = array('status' => FALSE,'error_code' => '612','message' => 'meterid invalid','data' => $res_result);
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$in_msisdn.']');
            $this->response($data);    
        }
        $this->_Check_agent_data($in_agenid, '614', $trace_id,$res_result);
        
        $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_POSPAID);
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid  = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                $biaya_adm  = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->List_product('.PROVIDER_CODE_POSPAID.')');
            $this->response($data);
        }
        //*****************************************
        //lokasi hit ke server pulsa
        //*****************************************
        $URL = URLPLN_POSTPAID_INQ;
        $URL = $URL."?product_code=".$productid;
        $URL = $URL."&trx_id=".$trans_id;
        $URL = $URL."&cust_id=".$no_meter;
        //product_code=&trx_id=&cust_id=
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        if($data_topup){
            $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
            $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
            $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
            $topup_partner_id       = isset($data_topup->partner_id) ? $data_topup->partner_id : NULL;
            $topup_product_code     = isset($data_topup->product_code) ? $data_topup->product_code : NULL;
            $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
            $topup_cust_id          = isset($data_topup->cust_id) ? $data_topup->cust_id : NULL;
            $topup_cust_name        = isset($data_topup->cust_name) ? $data_topup->cust_name : NULL;
            $topup_rec_payable      = isset($data_topup->rec_payable) ? $data_topup->rec_payable : NULL;
            $topup_rec_rest         = isset($data_topup->rec_rest) ? $data_topup->rec_rest : NULL;
            $topup_reffno_pln       = isset($data_topup->reffno_pln) ? $data_topup->reffno_pln : NULL;
            $topup_unit_svc         = isset($data_topup->unit_svc) ? $data_topup->unit_svc : NULL;
            $topup_svc_contact      = isset($data_topup->svc_contact) ? $data_topup->svc_contact : NULL;
            $topup_tariff           = isset($data_topup->tariff) ? $data_topup->tariff : NULL;
            $topup_daya             = isset($data_topup->daya) ? $data_topup->daya : NULL;
            $topup_admin_fee        = isset($data_topup->admin_fee) ? $data_topup->admin_fee : 0;
            $topup_periode1         = isset($data_topup->periode1) ? $data_topup->periode1 : NULL;
            $topup_periode2         = isset($data_topup->periode2) ? $data_topup->periode2 : NULL;
            $topup_periode3         = isset($data_topup->periode3) ? $data_topup->periode3 : NULL;
            $topup_periode4         = isset($data_topup->provider) ? $data_topup->provider : NULL;
            $topup_duedate          = isset($data_topup->duedate) ? $data_topup->duedate : NULL;
            $topup_mtr_date         = isset($data_topup->mtr_date) ? $data_topup->mtr_date : NULL;
            $topup_bill_amount      = isset($data_topup->bill_amount) ? $data_topup->bill_amount : 0;
            $topup_insentif         = isset($data_topup->insentif) ? $data_topup->insentif : 0;
            $topup_ppn              = isset($data_topup->ppn) ? $data_topup->ppn : 0;
            $topup_penalty          = isset($data_topup->penalty) ? $data_topup->penalty : 0;
            $topup_lwbp_last        = isset($data_topup->lwbp_last) ? $data_topup->lwbp_last : NULL;
            $topup_lwbp_crr         = isset($data_topup->lwbp_crr) ? $data_topup->lwbp_crr : NULL;
            $topup_wbp_last         = isset($data_topup->wbp_last) ? $data_topup->wbp_last : NULL;
            $topup_wbp_crr          = isset($data_topup->wbp_crr) ? $data_topup->wbp_crr : NULL;
            $topup_kvarh_last       = isset($data_topup->kvarh_last) ? $data_topup->kvarh_last : NULL;
            $topup_kvarh_crr        = isset($data_topup->kvarh_crr) ? $data_topup->kvarh_crr : NULL;
        }

        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);

        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid product','data' => $res_result);          
            }elseif($topup_respon_code == '10' || $topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '603','message' => 'plnid tidak terdaftar','data' => $res_result);       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '601','message' => 'tagihan lunas','data' => $res_result);
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '602','message' => 'saldo insufficent balance','data' => $res_result);
            }else{
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            }            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        } 
        if(!$this->Tab_model->Ins_Tbl('com_pln_ses',$data_topup)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $res_result);
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
        }
        
//        $this->sub_tagihan  = $topup_bill_amount + $topup_ppn + $topup_penalty;
//        $this->tot_tagihan  = $this->sub_tagihan - $topup_insentif;
        $this->tot_tagihan  = $topup_bill_amount;
        $this->total        = $this->tot_tagihan + $biaya_adm;
        //$this->logAction('data', $trace_id, array(), 'tagihan: '.$this->tot_tagihan.' + admin: '.$biaya_adm.' = '.);
        $arr_upd = array(
            'log_trace'  => $trace_id,
            'tagihan'  => $this->tot_tagihan,
            'biaya_adm' => $biaya_adm,
            'total' => $this->total
        );
        $this->LogAction('update', $trace_id, $arr_upd, 'Pay_model->Upd_logpln');
        $this->Pay_model->Upd_logpln($topup_transid,$arr_upd);
        
        if(is_array($topup_daya)){ $topup_daya = "";         }
        if(is_array($topup_cust_id)){ $topup_cust_id = "";         }
        if(is_array($topup_cust_name)){ $topup_cust_name = "";         }
        $res_result = array(
            'meterid'       => $topup_cust_id,
            'cust_name'     => $topup_cust_name,
            'daya'          => $topup_daya,
            'tagihan'       => $this->Rp($this->tot_tagihan),
            'adm'           => $this->Rp($biaya_adm),            
            'total'         => $this->Rp($this->total),            
            'code'          => $trace_id            
        );
        $this->LogAction('info', $trace_id, $res_result, 'tagihan');
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'sucess','data' => $res_result);
        $this->LogAction('response', $trace_id, $data, '');
        $this->response($data);        
    }
    public function Topup_mfinance_inq_post() {
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $res_result = array(
            'custid'        => '',
            'cust_name'     => '',
            'tagihan'       => '',
            'adm'           => '',            
            'total'         => '',            
            'code'          => ''            
        );
        $in_nik         = $this->input->post('custid') ? $this->input->post('custid') : '';
        $in_productid   = $this->input->post('productid') ? $this->input->post('productid') : PROVIDER_CODE_FINANCE;
        $in_agenid      = $this->input->post('agentid') ? $this->input->post('agentid') : '';
        $this->_Check_prm_data($in_nik,'611', $trace_id, 'custid isempty',$res_result);
        $this->_Check_prm_data($in_nik,'613', $trace_id, 'agentid isempty',$res_result);
        if (!is_numeric($in_nik))	{
            $data = array('status' => FALSE,'error_code' => '612','message' => 'custid invalid','data' => $res_result);
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$in_nik.']');
            $this->response($data);    
        }
        $this->_Check_agent_data($in_agenid, '614', $trace_id,$res_result);        
        
        $res_dta_postpaid    = $this->Pay_model->Pulsa_by_product($in_productid,'FINANCE');
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid  = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                //$biaya_adm  = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('info', $trace_id, $data, 'failed, Pay_model->List_product('.$in_productid.')');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }
        //*****************************************
        //lokasi hit ke server pulsa
        //*****************************************
        $URL = URL_MFINANCE_INQ;
        $URL = $URL."?product_code=".$productid;
        $URL = $URL."&trx_id=".$trans_id;
        $URL = $URL."&cust_id=".$in_nik;
        //product_code=&trx_id=&cust_id=
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }

        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        if($data_topup){
            $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
            $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
            $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
            $topup_partner_id       = isset($data_topup->partner_id) ? $data_topup->partner_id : NULL;
            $topup_product_code     = isset($data_topup->product_code) ? $data_topup->product_code : NULL;
            $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
            $topup_cust_id          = isset($data_topup->cust_id) ? $data_topup->cust_id : NULL;
            $topup_cust_name        = isset($data_topup->cust_name) ? $data_topup->cust_name : NULL;
            $topup_bill_amount      = isset($data_topup->amount) ? $data_topup->amount : 0;
            $topup_admin_fee         = isset($data_topup->admin_fee) ? $data_topup->admin_fee : 0;
            
        }

        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $res_result);
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);

        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid product','data' => '');          
            }elseif($topup_respon_code == '10' || $topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '603','message' => 'custid tidak terdaftar','data' => '');       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '601','message' => 'tagihan lunas','data' => '');
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '602','message' => 'saldo insufficent balance','data' => '');
            }else{
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            }            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }
        
        
        if(!$this->Tab_model->Ins_Tbl('com_mfinance_ses',$data_topup)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $res_result);
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            //return;
        }
        
//        $this->sub_tagihan  = $topup_bill_amount + $topup_ppn + $topup_penalty;
//        $this->tot_tagihan  = $this->sub_tagihan - $topup_insentif;
        $this->tot_tagihan  = $topup_bill_amount;
        $this->total        = $this->tot_tagihan + $topup_admin_fee;
        //$this->logAction('data', $trace_id, array(), 'tagihan: '.$this->tot_tagihan.' + admin: '.$biaya_adm.' = '.);
        $arr_upd = array(
            'log_trace'  => $trace_id,
            'total' => $this->total
        );
        $this->LogAction('update', $trace_id, $arr_upd, 'Pay_model->Upd_logmfinance("'.$topup_transid.'")');
        $this->Pay_model->Upd_logmfinance($topup_transid,$arr_upd);
        $res_result = array(
            'custid'       => $topup_cust_id,
            'cust_name'     => $topup_cust_name,
            'tagihan'       => $this->Rp($this->tot_tagihan),
            'adm'           => $this->Rp($topup_admin_fee),            
            'total'         => $this->Rp($this->total),            
            'code'          => $topup_transid            
        );
        $this->LogAction('info', $trace_id, $res_result, 'tagihan');
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'sucess','data' => $res_result);
        $this->LogAction('response', $trace_id, $data, '');
        $this->response($data);       
        //log_message('2', 'message');    
    }
    public function Pam_list_get() {
        $trace_id = $this->logid();
        $this->logheader($traceid);
        $res_list = $this->Pay_model->Listpulsa('PAM');
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("PAM")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
                if(is_integer($val_list->product_alias) == TRUE)
                {
                    $alias = $this->Rp($val_list->product_alias);
                }else{
                    $alias = $val_list->product_alias;
                }
                $arr[] = array(
                    'type'      => $val_list->type,
                    'provider'  => $val_list->provider,
                    'produk'    => $val_list->product_id,
                    'product_alias'    => $alias,                    
                    'harga'     => $this->Rp($val_list->price_selling),
                    'status'    => $val_list->status
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600',
                'message' => 'success',
                'list' => $arr);
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '601',
                'message' => 'empty', 
                'list' => '');
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Game_list_get() {
        $trace_id = $this->logid();
        $this->logheader($traceid);
        $res_list = $this->Pay_model->Listpulsa('GAME');
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("GAME")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
                if(is_integer($val_list->product_alias) == TRUE)
                {
                    $alias = $this->Rp($val_list->product_alias);
                }else{
                    $alias = $val_list->product_alias;
                }
                $arr[] = array(
                    'type'      => $val_list->type,
                    'provider'  => $val_list->provider,
                    'produk'    => $val_list->product_id,
                    'product_alias'    => $alias,                    
                    'harga'     => $this->Rp($val_list->price_selling),
                    'status'    => $val_list->status
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600',
                'message' => 'success',
                'list' => $arr);
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '601',
                'message' => 'empty', 
                'list' => '');
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Finance_list_get() {
        $trace_id = $this->logid();
        $this->logheader($traceid);
        $res_list = $this->Pay_model->Listpulsa('FINANCE');
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("FINANCE")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
                if(is_integer($val_list->product_alias) == TRUE)
                {
                    $alias = $this->Rp($val_list->product_alias);
                }else{
                    $alias = $val_list->product_alias;
                }
                $arr[] = array(
                    'type'      => $val_list->type,
                    'provider'  => $val_list->provider,
                    'produk'    => $val_list->product_id,
                    'product_alias'    => $alias,                    
                    'harga'     => $this->Rp($val_list->price_selling),
                    'status'    => $val_list->status
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600',
                'message' => 'success',
                'list' => $arr);
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '601',
                'message' => 'empty', 
                'list' => '');
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Internet_list_get() {
        $trace_id = $this->logid();
        $this->logheader($traceid);
        $res_list = $this->Pay_model->Listpulsa('INTERNET');
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("INTERNET")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
                if(is_integer($val_list->product_alias) == TRUE)
                {
                    $alias = $this->Rp($val_list->product_alias);
                }else{
                    $alias = $val_list->product_alias;
                }
                $arr[] = array(
                    'type'      => $val_list->type,
                    'provider'  => $val_list->provider,
                    'produk'    => $val_list->product_id,
                    'product_alias'    => $alias,                    
                    'harga'     => $this->Rp($val_list->price_selling),
                    'status'    => $val_list->status
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600',
                'message' => 'success',
                'list' => $arr);
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '601',
                'message' => 'empty', 
                'list' => '');
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Tv_list_get() {
        $trace_id = $this->logid();
        $this->logheader($traceid);
        $res_list = $this->Pay_model->Listpulsa('TV');
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("TV")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
                if(is_integer($val_list->product_alias) == TRUE)
                {
                    $alias = $this->Rp($val_list->product_alias);
                }else{
                    $alias = $val_list->product_alias;
                }
                $arr[] = array(
                    'type'      => $val_list->type,
                    'provider'  => $val_list->provider,
                    'produk'    => $val_list->product_id,
                    'product_alias'    => $alias,                    
                    'harga'     => $this->Rp($val_list->price_selling),
                    'status'    => $val_list->status
                );
            }
            $data = array('status' => TRUE,
                'errorcode' => '600',
                'message' => 'success',
                'list' => $arr);
            $this->logAction('response', $trace_id, $arr, 'success');
            $this->response($data);
        }
        else{
            $data = array('status' => FALSE,
                'errorcode' => '601',
                'message' => 'empty', 
                'list' => '');
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }
        //log_message('2', 'message');
    }
    public function Topup_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid = $this->input->post('agentid') ? $this->input->post('agentid') : ''; //**
        $in_msisdn = $this->input->post('msisdn') ? $this->input->post('msisdn') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : 'agent'; // value of agent or nasabah[90]
        $in_produk = $this->input->post('produk') ? $this->input->post('produk') : '';        
        $in_kdepin = $this->input->post('pin') ? $this->input->post('pin') : '9999';       //**
        
        $this->_Check_prm_topup($in_msisdn,'611',$trace_id, 'agentid isempty');   //***
        $this->_Check_prm_topup($in_agenid,'612',$trace_id, 'in_agenid isempty');   //***
        $this->_Check_prm_topup($in_msisdn,'614',$trace_id, 'msisdn isempty');    // cek parameter
        $this->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty');    // -
        $this->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin isempty');       //
        $this->_Check_agent($in_agenid,'621',$trace_id);            //***
        //$this->_Check_kdpin($in_agenid, $in_kdepin,'622',$trace_id); // pin
        if (strlen($in_msisdn) < 8)	{            
            $data = array('status' => FALSE,'error_code' => '623','message' => 'msisdn invalid','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, msisdn lenght ['.strlen($in_msisdn).']');
            $this->response($data);  
        }
        if (!is_numeric($in_msisdn))	{
            $data = array('status' => FALSE,'error_code' => '623','message' => 'msisdn invalid','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$in_msisdn.']');
            $this->response($data);    
        }
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('no_rekening') ?: '';
            $this->_Check_prm($in_rkning,'613',$trace_id, 'rekening isempty');   //***            
        }elseif(strtolower($in_paytype) == "agent"){
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agenid.']');
            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }
                
                $this->_Check_prm($agn_norekening, '627', $trace_id, 'rekening agent invalid');
                if($agn_active == "0"){
                    $data = array('status' => FALSE,'error_code' => '628','message' => 'account agent inactive','sn' => '','code' => '');
                    $this->logAction('response', $trace_id, $data, 'account agent inactive');
                    $this->response($data); 
                }
                $in_rkning =  $agn_norekening;
            }else{
                $data = array('status' => FALSE,'error_code' => '625','message' => 'norekening invalid','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed, no response or empty data');
                $this->response($data); 
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '617','message' => 'paytype invalid','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Pulsa_by_product($in_produk,'PULSA');
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '624','message' => 'produk close','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Pulsa_by_product('.$in_produk.',PULSA)');
            $this->response($data);    
        }
        foreach ($res_product as $sub_product) {
            $r_provider     = $sub_product->provider;
            $r_kode_produk     = $sub_product->product_id;
            $r_type     = $sub_product->type;
            //$r_price_original     = $sub_product->price_original;
            $r_price_selling     = $sub_product->price_selling;
            $r_product_alias     = $sub_product->product_alias;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        if(!$res_cek_rek){            
            $data = array('status' => FALSE,'error_code' => '625','message' => 'rekening invalid','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
            $this->response($data);
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        if(strtolower($in_paytype) == "nasabah"){
            if(floatval(round($r_price_selling)) > round($cek_saldo_akhir)){
                $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
                $this->response($data);

            }
        }
        
        //*****************************************
        //lokasi hit ke server pulsa
        //*****************************************
        $URL = URLPULSA;
        $URL = $URL."?no_hp=".$in_msisdn;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);
        $URL = $URL."&jumlah=".$r_product_alias;
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        //$res_call_hit_topup = '{"status":{"status":{"status":"OK","respon_code":"63","request_id":"628551000727","message ":"08\/11\/16 12:15 ISI SN5 KE 081319292741>user di-block INFO : Perubahan harga XL, AXIS, INDOSAT DATA utk RS silahkan cek harga, thx.","saldo":"081319292741"}}}';
        
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->sn) ? $data_topup->sn : NULL;
        $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            //return;
        }
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_msisdn,
            'product'       => $in_produk,
            'nominal'       => $r_type,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $r_provider,
            'price_selling' => $r_price_selling,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            return;
        }
        if((strtolower($topup_respon_code) == '00') //sukses
            || (strtolower($topup_respon_code) == '68')  //pending
            ){ //
            $this->logAction('insert', $trace_id, array(), 'resulrt,responcode ['.$topup_respon_code.'] >> '.$topup_message);
        }
        else{
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid pin','sn' => '','code' => '');                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '624','message' => 'invalid product','sn' => '','code' => '');          
            }elseif($topup_respon_code == '10' || $topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'invalid msisdn','sn' => '','code' => '');       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' => '','code' => '');
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            }elseif($topup_respon_code == '94'){
                $data = array('status' => FALSE,'error_code' => '635','message' => 'Double Transaction','sn' => '','code' => '');
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }        
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc(PULSA_CODE);
        $in_tob                         = $this->_Kodetrans_by_tob(PULSA_MYCODE);
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_msisdn." ".  $this->Rp($r_price_selling)." SN:.".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>PULSA_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>PULSA_CODE,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("TABTRANS")');
         if(!$res_ins){
            
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }
        $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);

        $res_call_tab   = $this->Tab_model->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
        foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

            $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
            $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
            $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
        }
        //receiver
        
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }        

        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
            $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
        }
        $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $r_price_selling                                                             
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $r_price_selling, 
                'KREDIT'        =>  0
            )
        );       

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
        $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
        if($res_run){            
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code'=> $trace_id);
            $dataupd = array('master_id' => $gen_id_MASTER);
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
            
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
            $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
            $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');          
            $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
            $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }          
    } //pulsa
    public function Topup_pln_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid      = $this->input->post('agentid') ?: ''; //**
        $in_pln         = $this->input->post('plnid') ?: '';  // post data
        $in_paytype     = $this->input->post('paytype') ?: ''; // value of agent or nasabah[90]
        $in_produk      = $this->input->post('produk') ?: '';        
        $in_kdepin      = $this->input->post('pin') ?: '9999';       //**
        
        
        $this->_Check_prm_topup($in_agenid,'612',$trace_id, 'in_agenid isempty');   //***
        $this->_Check_prm_topup($in_pln,'614',$trace_id, 'plnid isempty');    // cek parameter
        $this->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty');    // -
        $this->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin isempty');       //
        $this->_Check_agent_topup($in_agenid,'621',$trace_id);            //***
        $this->_Check_kdpin($in_agenid, $in_kdepin,'622',$trace_id); // pin
        if (strlen($in_pln) < 8)
	{            
            $data = array('status' => FALSE,'error_code' => '623','message' => 'plnid invalid','sn' => '','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, plnid lenght ['.strlen($in_pln).']');
            $this->response($data);  
        }
        if (!is_numeric($in_pln))
	{
            $data = array('status' => FALSE,'error_code' => '623','message' => 'plnid invalid','sn' => '','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, plnid isnot numeric ['.$in_pln.']');
            $this->response($data);    
        }
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('rekening');
            $this->_Check_prm($in_rkning,'613',$trace_id, 'rekening isempty');   //***            
        }elseif(strtolower($in_paytype) == "agent"){
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agenid.']');
            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }
                
                $this->_Check_prm($agn_norekening, '627', $trace_id, 'rekening agent invalid');
                if($agn_active == "0"){
                    $data = array('status' => FALSE,'error_code' => '628','message' => 'account agent inactive', 'sn' =>'','code'=>'');
                    $this->logAction('response', $trace_id, $data, 'account agent inactive');
                    $this->response($data); 
                }
                $in_rkning =  $agn_norekening;
            }else{
                $data = array('status' => FALSE,'error_code' => '625','message' => 'norekening invalid', 'sn' =>'','code'=>'');
                $this->logAction('response', $trace_id, $data, 'failed, no response or empty data');
                $this->response($data); 
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '617','message' => 'paytype invalid','sn'=>'','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Pulsa_by_product($in_produk,'PLN');
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '624','message' => 'produk close','sn' =>'','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Pulsa_by_product('.$in_produk.',PULSA)');
            $this->response($data);    
        }
        foreach ($res_product as $sub_product) {
            $r_provider     = $sub_product->provider;
            $r_kode_produk     = $sub_product->product_id;
            $r_type             = $sub_product->type;
            //$r_price_original     = $sub_product->price_original;
            $r_price_selling     = $sub_product->price_selling;
            $r_product_alias     = $sub_product->product_alias;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        if(!$res_cek_rek){            
            $data = array('status' => FALSE,'error_code' => '625','message' => 'rekening invalid','sn' =>'','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
            $this->response($data);
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        if(floatval(round($r_price_selling)) > round($cek_saldo_akhir)){
            $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' =>'','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
            $this->response($data);
            
        }        
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URLPLN;
        $URL = $URL."?id_pln=".$in_pln;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);
        //$URL = $URL."&jumlah=".$r_product_alias;
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        //$res_call_hit_topup = '{"status":{"status":{"status":"OK","respon_code":"63","request_id":"628551000727","message ":"08\/11\/16 12:15 ISI SN5 KE 081319292741>user di-block INFO : Perubahan harga XL, AXIS, INDOSAT DATA utk RS silahkan cek harga, thx.","saldo":"081319292741"}}}';
        
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->respon_code) ? $data_topup->respon_code : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->kode_token) ? $data_topup->kode_token : NULL;
        $topup_transid          = isset($data_topup->id) ? $data_topup->id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            //return;
        }
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_pln,
            'product'       => $in_produk,
            'nominal'       => $r_product_alias,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $r_provider,
            'price_selling' => $r_price_selling,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' =>'','code'=>'');
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            return;
        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid pin','sn' =>'','code'=>'');                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '624','message' => 'invalid product','sn' =>'','code'=>'');          
            }elseif($topup_respon_code == '10'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'invalid plnid','sn' =>'','code'=>'');       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' => '','code'=>'');
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }elseif($topup_respon_code == '68'){
                $data = array('status' => FALSE,'error_code' => '634','message' => 'pending','sn' => '','code'=>'');
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }        
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc(PLN_TOKEN_CODE);
        $in_tob                         = $this->_Kodetrans_by_tob(PLN_TOKEN_MYCODE);
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_pln." ".  $this->Rp($r_price_selling)." SN:92372892749748";       
        $in_KUITANSI    = $this->_Kuitansi();                    
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>PLN_TOKEN_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>PLN_TOKEN_CODE,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("TABTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error' => 'error db', 'data' => '');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }
        $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
        /*$res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_rkning);
        if(!$res_sum12){
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);              
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code'=>'');
            $this->logAction('select', $trace_id, array(), 'failed, Tab_model->Sum_1_2_taptrans('.$in_rkning.')');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
            //array('sn' => '')return;
        }
        foreach ($res_sum12 as $sub12) {
            $setoran12      = $sub12->SETORAN;
            $penarikan12    = $sub12->PENARIKAN;
            $setoran_bunga12    = $sub12->SETORAN_BUNGA;
            $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
        }
        
        $hasil      = $setoran12 - $penarikan12;
        $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
                            
        $datatabung =   array( //rekening sender
                                'SALDO_AKHIR' => $hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga);       
        $res_upd_tab = $this->Tab_model->upd_tabung($in_rkning,$datatabung);
        if(!$res_upd_tab){    
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID); 
            $this->logAction('update', $trace_id, array(), 'failed, Tab_model->upd_tabung('.$in_rkning.','.$datatabung.')');
            $this->logAction('delete', $trace_id, array(), 'rollback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }  
         * */
             
        
        $res_call_tab   = $this->Tab_model->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
        foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

            $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
            $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
            $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
        }
        //receiver
        
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }        

        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
            $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
        }
        $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $r_price_selling                                                             
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $r_price_selling, 
                'KREDIT'        =>  0
            )
        );       

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
        $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
        if($res_run){
            $arrdata = array(
                'sn' => $topup_no_serial
            );
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code' => $trace_id);
            $dataupd = array('master_id' => $gen_id_MASTER,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
            $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code'=>'');
            $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');          
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }          
    }
    public function Topup_pln_postpaid_pay_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('agentid') ? $this->input->post('agentid') : ''; //**
        $in_pln     = $this->input->post('meterid')   ? $this->input->post('meterid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : ''; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '9999';       //**
        $in_codepln = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $this->_Check_prm_data($in_agenid,'612',$trace_id, 'agentid isempty');   //***
        $this->_Check_prm_data($in_pln,'611',$trace_id, 'meterid isempty');    // cek parameter
        $this->_Check_prm_data($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $this->_Check_prm_data($in_codepln,'614',$trace_id, 'code isempty');       //
        $this->_Check_agent_data($in_agenid,'612',$trace_id);            //***
        $this->_Check_kdpin_data($in_agenid, $in_kdepin,'615',$trace_id); // pin
        if (strlen($in_pln) < 8)
	{            
            $data = array('status' => FALSE,'error_code' => '616','message' => 'meterid invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, plnid lenght ['.strlen($in_pln).']');
            $this->response($data);  
        }
        if (!is_numeric($in_pln))
	{
            $data = array('status' => FALSE,'error_code' => '616','message' => 'meterid invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, plnid isnot numeric ['.$in_pln.']');
            $this->response($data);    
        }
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('rekening');
            $this->_Check_prm_data($in_rkning,'617',$trace_id, 'rekening isempty');   //***            
        }elseif(strtolower($in_paytype) == "agent"){
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agenid.']');
            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }
                
                $this->_Check_prm_data($agn_norekening, '619', $trace_id, 'norekening invalid');
                if($agn_active == "0"){
                    $data = array('status' => FALSE,'error_code' => '621','message' => 'account agent inactive','data'=>array('sn' => '','code' => ''));
                    $this->logAction('response', $trace_id, $data, 'account agent inactive');
                    $this->response($data); 
                }
                $in_rkning =  $agn_norekening;
            }else{
                $data = array('status' => FALSE,'error_code' => '619','message' => 'norekening invalid','data'=>array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, no response or empty data');
                $this->response($data); 
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '618','message' => 'paytype invalid','data'=>array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Pln_ses_by_code($in_codepln,$in_pln);
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '622','message' => 'code','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Pln_ses_by_codePln_ses_by_code('.$in_codepln.','.$in_pln.')');
            $this->response($data);    
        }
        foreach ($res_product as $sub_product) {
            $topup_status           = isset($sub_product->status) ? $sub_product->status : '' ;
            $topup_respon_code      = isset($sub_product->rc) ? $sub_product->rc : '';
            $topup_message          = isset($sub_product->message) ? $sub_product->message : '';
            $topup_partner_id       = isset($sub_product->partner_id) ? $sub_product->partner_id : NULL;
            $topup_product_code     = isset($sub_product->product_code) ? $sub_product->product_code : NULL;
            $topup_transid          = isset($sub_product->trx_id) ? $sub_product->trx_id : NULL;
            $topup_cust_id          = isset($sub_product->cust_id) ? $sub_product->cust_id : NULL;
            $topup_cust_name        = isset($sub_product->cust_name) ? $sub_product->cust_name : NULL;
            $topup_rec_payable      = isset($sub_product->rec_payable) ? $sub_product->rec_payable : NULL;
            $topup_rec_rest         = isset($sub_product->rec_rest) ? $sub_product->rec_rest : NULL;
            $topup_reffno_pln       = isset($sub_product->reffno_pln) ? $sub_product->reffno_pln : NULL;
            $topup_unit_svc         = isset($sub_product->unit_svc) ? $sub_product->unit_svc : NULL;
            $topup_svc_contact      = isset($sub_product->svc_contact) ? $sub_product->svc_contact : NULL;
            $topup_tariff           = isset($sub_product->tariff) ? $sub_product->tariff : NULL;
            $topup_daya             = isset($sub_product->daya) ? $sub_product->daya : NULL;
            $topup_admin_fee        = isset($sub_product->admin_fee) ? $sub_product->admin_fee : 0;
            $topup_periode1         = isset($sub_product->periode1) ? $sub_product->periode1 : NULL;
            $topup_periode2         = isset($sub_product->periode2) ? $sub_product->periode2 : NULL;
            $topup_periode3         = isset($sub_product->periode3) ? $sub_product->periode3 : NULL;
            $topup_periode4         = isset($sub_product->provider) ? $sub_product->provider : NULL;
            $topup_duedate          = isset($sub_product->duedate) ? $sub_product->duedate : NULL;
            $topup_mtr_date         = isset($sub_product->mtr_date) ? $sub_product->mtr_date : NULL;
            $topup_bill_amount      = isset($sub_product->bill_amount) ? $sub_product->bill_amount : 0;
            $topup_insentif         = isset($sub_product->insentif) ? $sub_product->insentif : 0;
            $topup_ppn              = isset($sub_product->ppn) ? $sub_product->ppn : 0;
            $topup_penalty          = isset($sub_product->penalty) ? $sub_product->penalty : 0;
            $topup_lwbp_last        = isset($sub_product->lwbp_last) ? $sub_product->lwbp_last : NULL;
            $topup_lwbp_crr         = isset($sub_product->lwbp_crr) ? $sub_product->lwbp_crr : NULL;
            $topup_wbp_last         = isset($sub_product->wbp_last) ? $sub_product->wbp_last : NULL;
            $topup_wbp_crr          = isset($sub_product->wbp_crr) ? $sub_product->wbp_crr : NULL;
            $topup_kvarh_last       = isset($sub_product->kvarh_last) ? $sub_product->kvarh_last : NULL;
            $topup_kvarh_crr        = isset($sub_product->kvarh_crr) ? $sub_product->kvarh_crr : NULL;
            $topup_tagihan          = isset($sub_product->tagihan) ? $sub_product->tagihan : NULL;
            $topup_biaya_adm        = isset($sub_product->biaya_adm) ? $sub_product->biaya_adm : NULL;
            $topup_total            = isset($sub_product->total) ? $sub_product->total : NULL;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        if(!$res_cek_rek){            
            $data = array('status' => FALSE,'error_code' => '619','message' => 'rekening invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
            $this->response($data);
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        $this->logAction('info', $trace_id, array(), 'total biaya : '.$topup_total);
        $this->logAction('info', $trace_id, array(), 'saldo rekening : '.$cek_saldo_akhir);
        if(floatval(round($topup_total)) > round($cek_saldo_akhir)){
            $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
            $this->response($data);
            
        }
        
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URLPLN_POSTPAID_PAY;
        $URL = $URL."?partner_id=".$topup_partner_id;
        $URL = $URL."&product_code=".$topup_product_code;
        $URL = $URL."&trx_id=".$topup_transid;
        $URL = $URL."&cust_id=".$topup_cust_id;
        $URL = $URL."&cust_name=".rawurlencode($topup_cust_name);
        $URL = $URL."&rec_payable=".$topup_rec_payable;
        $URL = $URL."&rec_rest=".$topup_rec_rest;
        $URL = $URL."reffno_pln=".$topup_reffno_pln;
        $URL = $URL."&unit_svc=".rawurlencode($topup_unit_svc);
        $URL = $URL."&svc_contact=".$topup_svc_contact;
        $URL = $URL."&tariff=".rawurlencode($topup_tariff);
        $URL = $URL."&daya=".$topup_daya;
        $URL = $URL."&admin_fee=".round($topup_admin_fee);
        $URL = $URL."&periode1=".$topup_periode1;
        $URL = $URL."&periode2=".$topup_periode2;
        $URL = $URL."&periode3=".$topup_periode3;
        $URL = $URL."&periode4=".$topup_periode4;
        $URL = $URL."&mtr_date=".$topup_mtr_date;
        $URL = $URL."&bill_amount=".round($topup_bill_amount);
        $URL = $URL."&insentif=".round($topup_insentif);
        $URL = $URL."&ppn=".round($topup_ppn);
        $URL = $URL."&penalty=".round($topup_penalty);
        $URL = $URL."&lwbp_last=".$topup_lwbp_last;
        $URL = $URL."&lwbp_crr=".$topup_lwbp_crr;
        $URL = $URL."&wbp_last=".$topup_wbp_last;
        $URL = $URL."&wbp_crr=".$topup_wbp_crr;
        $URL = $URL."&kvarh_last=".$topup_kvarh_last;
        $URL = $URL."&kvarh_crr=".$topup_kvarh_crr;

        //partner_id=&product_code=&trx_id=&cust_id=&cust_name=&rec_payable=&rec_rest=&reffno_pln=&unit_svc=
        //&svc_contact=&tariff=&daya=&admin_fee=&periode1=&periode2=&periode3=&periode4=&duedate=&mtr_date=
        //&bill_amount=&insentif=&ppn=&penalty=&lwbp_last=&lwbp_crr=&wbp_last=&wbp_crr=&kvarh_last=&kvarh_crr=

        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        //$res_call_hit_topup = $this->Hitget($URL);
        
        $res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"16122940795468217538","cust_id":"654321198700","cust_name":"SUPARDI NATSIR","rec_payable":"1","rec_rest":"0reffno_pln=PLN000003","reffno_pln":[],"unit_svc":"UNIT A","svc_contact":"0217900001","tariff":"TARIF C","daya":"500","admin_fee":"7000","periode1":"201607","periode2":[],"periode3":[],"periode4":[],"duedate":[],"mtr_date":"2012-04-26","bill_amount":"25000","insentif":"1000","ppn":"5000","penalty":"3000","lwbp_last":"500","lwbp_crr":"100","wbp_last":"600","wbp_crr":"100","kvarh_last":"1100","kvarh_crr":"100","pay_datetime":"20161230162536","footer_message":"OK","receipt_code":"12345","message":"Berhasil"}';
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->receipt_code) ? $data_topup->receipt_code : NULL;
        $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            return;
        }
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_pln,
            'product'       => $topup_product_code,
            'nominal'       => $topup_total,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $topup_provider,
            'price_selling' => $topup_total,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            return;
        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '613','message' => 'invalid pin','data' => array('sn' => '','code' => ''));                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '632','message' => 'invalid product','data' => array('sn' => '','code' => ''));          
            }elseif($topup_respon_code == '10'){
                $data = array('status' => FALSE,'error_code' => '611','message' => 'invalid plnid','data' => array('sn' => '','code' => ''));       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '68'){
                $data = array('status' => FALSE,'error_code' => '634','message' => 'pending','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }        
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc(PLN_POSTPAID_CODE);
        $in_tob                         = $this->_Kodetrans_by_tob(PLN_POSTPAID_MYCODE);
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $this->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>PLN_POSTPAID_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_tagihan,
            'ADM'               =>$topup_biaya_adm,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>PLN_POSTPAID_CODE,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("TABTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error' => 'error db', 'data' => '');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }
         $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
        $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
        /*
        $res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_rkning);
        if(!$res_sum12){
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);              
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('select', $trace_id, array(), 'failed, Tab_model->Sum_1_2_taptrans('.$in_rkning.')');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
            return;
        }
        foreach ($res_sum12 as $sub12) {
            $setoran12      = $sub12->SETORAN;
            $penarikan12    = $sub12->PENARIKAN;
            $setoran_bunga12    = $sub12->SETORAN_BUNGA;
            $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
        }
        
        $hasil      = $setoran12 - $penarikan12;
        $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
                            
        $datatabung =   array( //rekening sender
                                'SALDO_AKHIR' => $hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga);
       
        $res_upd_tab = $this->Tab_model->upd_tabung($in_rkning,$datatabung);
        if(!$res_upd_tab){    
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID); 
            $this->logAction('update', $trace_id, array(), 'failed, Tab_model->upd_tabung('.$in_rkning.','.$datatabung.')');
            $this->logAction('delete', $trace_id, array(), 'rollback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }       
        */
        $res_call_tab   = $this->Tab_model->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
        foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

            $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
            $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
            $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
        }
        //receiver
        
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }        

        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
            $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE;
        }
        $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $topup_total                                                             
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $topup_total, 
                'KREDIT'        =>  0
            )
        );       

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
        $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
        if($res_run){
            $in_addpoin = array(
                'tid'   => $gen_id_TABTRANS_ID,
                'agent' => $in_agenid,
                'kode'  => PLN_POSTPAID_CODE,
                'jenis' => 'TAB',
                'nilai' => $topup_total ?: 0
            );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            $this->logAction('result', $trace_id, array(), 'response');
            
            $arrdata = array(
                'sn' => $topup_no_serial,
                'code' => $trace_id
            );
            array('sn' => $topup_no_serial,'code' => $trace_id);
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
            $dataupd = array('master_id' => $gen_id_MASTER,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
            $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');          
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }          
    }
    public function Topup_mfinance_pay_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('agentid') ? $this->input->post('agentid') : ''; //**
        $in_pln     = $this->input->post('custid')   ? $this->input->post('custid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : ''; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '9999';       //**
        $in_codemfinance = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $this->_Check_prm_data($in_agenid,'612',$trace_id, 'agentid isempty');   //***
        $this->_Check_prm_data($in_pln,'611',$trace_id, 'custid isempty');    // cek parameter
        $this->_Check_prm_data($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $this->_Check_prm_data($in_codemfinance,'614',$trace_id, 'code isempty');       //
        $this->_Check_agent_data($in_agenid,'612',$trace_id);            //***
        $this->_Check_kdpin_data($in_agenid, $in_kdepin,'615',$trace_id); // pin
        if (strlen($in_pln) < 8)
	{            
            $data = array('status' => FALSE,'error_code' => '616','message' => 'custid invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, custid lenght ['.strlen($in_pln).']');
            $this->response($data);  
        }
        if (!is_numeric($in_pln))
	{
            $data = array('status' => FALSE,'error_code' => '616','message' => 'custid invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, custid isnot numeric ['.$in_pln.']');
            $this->response($data);    
        }
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('rekening');
            $this->_Check_prm_data($in_rkning,'617',$trace_id, 'rekening isempty');   //***            
        }elseif(strtolower($in_paytype) == "agent"){
            $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agenid.']');
            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening = $sub_call_user_agent->no_rekening;
                    $agn_active = $sub_call_user_agent->active;
                    $agn_username = $sub_call_user_agent->username;
                }
                
                $this->_Check_prm_data($agn_norekening, '619', $trace_id, 'norekening invalid');
                if($agn_active == "0"){
                    $data = array('status' => FALSE,'error_code' => '621','message' => 'account agent inactive','data'=>array('sn' => '','code' => ''));
                    $this->logAction('response', $trace_id, $data, 'account agent inactive');
                    $this->response($data); 
                }
                $in_rkning =  $agn_norekening;
            }else{
                $data = array('status' => FALSE,'error_code' => '619','message' => 'norekening invalid','data'=>array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, no response or empty data');
                $this->response($data); 
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '618','message' => 'paytype invalid','data'=>array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Mfinance_ses_by_code($in_codemfinance,$in_pln);
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '622','message' => 'code','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Mfinance_ses_by_code('.$in_codepln.','.$in_pln.')');
            $this->logAction('response', $trace_id, array(),'error :'.$res_product);
            $this->response($data);    
        }
        foreach ($res_product as $sub_product) {
            $topup_product_code     = isset($sub_product->product_code) ? $sub_product->product_code : NULL;
            $topup_transid          = isset($sub_product->trx_id) ? $sub_product->trx_id : NULL;
            $topup_cust_id          = isset($sub_product->cust_id) ? $sub_product->cust_id : NULL;
            $topup_cust_name        = isset($sub_product->cust_name) ? $sub_product->cust_name : NULL;
            $topup_admin_fee        = isset($sub_product->admin_fee) ? $sub_product->admin_fee : 0;
            $topup_duedate          = isset($sub_product->duedate) ? $sub_product->duedate : NULL;
            $topup_bill_amount      = isset($sub_product->bill_amount) ? $sub_product->bill_amount : 0;
            $topup_total            = isset($sub_product->total) ? $sub_product->total : NULL;
        }
        
        //**** cek nasabah
        $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
        if(!$res_cek_rek){            
            $data = array('status' => FALSE,'error_code' => '619','message' => 'rekening invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
            $this->logAction('response', $trace_id, array(),'error :'.$res_cek_rek);
            $this->response($data);
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        $this->logAction('info', $trace_id, array(), 'total biaya : '.$topup_total);
        $this->logAction('info', $trace_id, array(), 'saldo rekening : '.$cek_saldo_akhir);
        if(floatval(round($topup_total)) > round($cek_saldo_akhir)){
            $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
            $this->response($data);
            
        }
        
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URL_MFINANCE_PAY;
        $URL = $URL."?";
        $URL = $URL."product_code=".$topup_product_code;
        $URL = $URL."&trx_id=".$topup_transid;
        $URL = $URL."&cust_id=".$topup_cust_id;
        $URL = $URL."&cust_name=".rawurlencode($topup_cust_name);
        $URL = $URL."&admin_fee=".round($topup_admin_fee);
        $URL = $URL."&no_installment=".round($topup_admin_fee);
        $URL = $URL."&due_date=".round($topup_admin_fee);
        $URL = $URL."&amount=".round($topup_bill_amount);

        //product_code=&trx_id=&cust_id=&cust_name=&amount=&no_installment=&due_date=&admin_fee=

        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        
        //$res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"16122940795468217538","cust_id":"654321198700","cust_name":"SUPARDI NATSIR","rec_payable":"1","rec_rest":"0reffno_pln=PLN000003","reffno_pln":[],"unit_svc":"UNIT A","svc_contact":"0217900001","tariff":"TARIF C","daya":"500","admin_fee":"7000","periode1":"201607","periode2":[],"periode3":[],"periode4":[],"duedate":[],"mtr_date":"2012-04-26","bill_amount":"25000","insentif":"1000","ppn":"5000","penalty":"3000","lwbp_last":"500","lwbp_crr":"100","wbp_last":"600","wbp_crr":"100","kvarh_last":"1100","kvarh_crr":"100","pay_datetime":"20161230162536","footer_message":"OK","receipt_code":"12345","message":"Berhasil"}';
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            return;
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->receipt_code) ? $data_topup->receipt_code : NULL;
        //$topup_transid_r          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            return;
        }
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_pln,
            'product'       => $topup_product_code,
            'nominal'       => $topup_total,
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'topup',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $topup_provider,
            'price_selling' => $topup_total,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        $res_pulsa_log = $this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log);
        if(!$res_pulsa_log){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->logAction('response', $trace_id, array(),'error :'.$res_pulsa_log);
            $this->response($data);
            //return;
        }
        if(strtolower($topup_respon_code) != '00'){
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '613','message' => 'invalid pin','data' => array('sn' => '','code' => ''));                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '632','message' => 'invalid product','data' => array('sn' => '','code' => ''));          
            }elseif($topup_respon_code == '10'){
                $data = array('status' => FALSE,'error_code' => '611','message' => 'invalid plnid','data' => array('sn' => '','code' => ''));       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '68'){
                $data = array('status' => FALSE,'error_code' => '634','message' => 'pending','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }        
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_desc_trans                  = $this->_Kodetrans_by_desc(MFINANCE_CODE);
        $in_tob                         = $this->_Kodetrans_by_tob(MFINANCE_MYCODE);
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $this->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>MFINANCE_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_total,
            'ADM'               =>'',
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>MFINANCE_CODE,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
            //$this->response($arr_data);
            
        $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("TABTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error' => 'error db', 'data' => '');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }
        $res_sum12  = $this->Tab_model->Sum_1_2_taptrans($in_rkning);
        if(!$res_sum12){
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);              
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('select', $trace_id, array(), 'failed, Tab_model->Sum_1_2_taptrans('.$in_rkning.')');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $this->logAction('response', $trace_id, $data, '');
            $this->logAction('response', $trace_id, array(),'error :'.$res_sum12);
            $this->response($data);
            //return;
        }
        foreach ($res_sum12 as $sub12) {
            $setoran12      = $sub12->SETORAN;
            $penarikan12    = $sub12->PENARIKAN;
            $setoran_bunga12    = $sub12->SETORAN_BUNGA;
            $penarikan_bunga12    = $sub12->PENARIKAN_BUNGA;
        }
        
        $hasil      = $setoran12 - $penarikan12;
        $bunga      = $setoran_bunga12 - $penarikan_bunga12;                        
                            
        $datatabung =   array( //rekening sender
                                'SALDO_AKHIR' => $hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $bunga);
       
        $res_upd_tab = $this->Tab_model->upd_tabung($in_rkning,$datatabung);
        if(!$res_upd_tab){    
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID); 
            $this->logAction('update', $trace_id, array(), 'failed, Tab_model->upd_tabung('.$in_rkning.','.$datatabung.')');
            $this->logAction('delete', $trace_id, array(), 'rollback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => '');
            $this->logAction('response', $trace_id, array(),'error :'.$res_upd_tab);
            $this->logAction('response', $trace_id, $data, '');
            
            $this->response($data);
        }       
        
        $res_call_tab   = $this->Tab_model->Tab_byrek($in_rkning);
        if($res_call_tab){
            foreach($res_call_tab as $sub_call_tab){
                $in_kode_integrasi =  $sub_call_tab->KODE_INTEGRASI;
            }
        }else{
            $this->logAction('response', $trace_id, array(),'error :'.$res_call_tab);
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agenid);
        $this->logAction('response', $trace_id, array(),'error :Sys_daftar_user_model->Perk_kas('.$in_agenid.')');
        if($res_call_kode_perk_kas){
            foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                $in_kode_perk_kas = $sub_call_kode_perk_kas->KODE_PERK_KAS;
            }
        }else{
            $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
            $this->logAction('response', $trace_id, array(),'error :'.serialize($res_call_kode_perk_kas));
        }
        $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
        $this->logAction('response', $trace_id, array(),'error :Perk_model->perk_by_kodeperk('.$in_kode_perk_kas.')');
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            //sender
        $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
        if($res_call_tab_integrasi_by_kd){
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
            }
        }else{
                $in_kode_perk_kas_in_integrasi          =  '';
                $in_kode_perk_hutang_pokok_in_integrasi =  '';
                $in_kode_perk_pend_adm_in_integrasi     =  ''; 
        }
        //receiver
        
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        if($res_call_perk_kode_all){
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total ?: 0;
            }        
        }else{
            $count_as_total = '';
        }
        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        if($res_call_tab_keyvalue){
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: 'TAB';
            }
        }else{
            $res_kode_jurnal = 'TAB';
        }
        $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                'USERID'            =>  $in_agenid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            );
        $ar_trans_detail = array(
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $topup_total                                                             
            ),array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $topup_total, 
                'KREDIT'        =>  0
            )
        );       

        $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
        $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
        $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
        if($res_run){
            
            $in_addpoin = array(
                'tid'   => $gen_id_TABTRANS_ID,
                'agent' => $in_agenid,
                'kode'  => MFINANCE_CODE,
                'jenis' => 'TAB',
                'nilai' => $topup_total ?: 0
            );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            if($res_poin){
                $this->logAction('result', $trace_id, array(), 'result');
            }else{
                $this->logAction('result', $trace_id, array(), 'error');
            }            

            $arrdata = array(
                'sn' => $topup_no_serial,
                'code' => $trace_id
            );
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
            $dataupd = array('master_id' => $gen_id_MASTER,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }else{            
            $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
            $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
            $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');          
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }          
    }
    public function Lasttrx_post() {            
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $in_nik         = $this->input->post('custid') ? $this->input->post('custid') : '';
        $in_productid   = $this->input->post('productid') ? $this->input->post('productid') : PROVIDER_CODE_FINANCE;
        $in_agenid      = $this->input->post('agentid') ? $this->input->post('agentid') : '';
        $this->_Check_prm_data($in_nik,'611', $trace_id, 'custid isempty');
        $this->_Check_prm_data($in_agenid,'613', $trace_id, 'agentid isempty');
        if (!is_numeric($in_nik))	{
            $data = array('status' => FALSE,'error_code' => '612','message' => 'custid invalid','data' => '');
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$in_nik.']');
            $this->response($data);    
        }
        $this->_Check_agent($in_agenid, '614', $trace_id);        
        $this->load->model('Pay_model');
        $g_code = '';
        $g_sn   = '';
        $g_dtm  = '';
         
        $res_dta    = $this->Pay_model->Lasttrx($in_productid,$in_nik);
        if($res_dta){
            foreach ($res_dta as $val) {
                $g_dtm  = $val->dtm     ? $val->dtm : '';
                $g_code  = $val->res_code  ? $val->res_code : '';
                $g_sn  = $val->res_sn  ? $val->res_sn : '';
            }
            
        }else{
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => '');
            $this->logAction('info', $trace_id, $data, 'failed, Pay_model->List_product('.$in_productid.')');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }
        if($g_code == "00"){
            $dta = array(
                'status_trx' => '00',
                'lastdtm' => $g_dtm,
                'sn' => $g_sn);
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->response($data);
        }elseif($g_code == "68"){
            $dta = array(
                'status_trx' => '68',
                'lastdtm' => $g_dtm,
                'sn' => $g_sn);
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->response($data);
        }elseif($g_code == ''){
            $dta = array(
                'status_trx' => '01',
                'lastdtm' => '',
                'sn' => '');
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->response($data);
        }else{
            $dta = array(
                'status_trx' => '02',
                'lastdtm' => $g_dtm,
                'sn' => $g_sn);
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->response($data);
        }
        
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
                $out_nokwi = "Tab.0001";
            }else{
                $skey = explode(".", $out_nokwi);
                $vkey0 = $skey[0];
                $vkey1 = $skey[1];
                if(strlen($vkey1) < 5){
                        $c = strlen($vkey1);
                        $c = 5 - $c;
                        $vv = '';
                        for($v=1;$v < $c;$v++){
                            $vv .= '0';
                        }
                }
                $out_nokwi = $vkey0.'.'.$vv.($vkey1 + 1);
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
        }else{
            $out_tob   = NULL;
        }
        return $out_tob;
    }
    protected function _Check_kdpin($in_agent_id = null, $in_kdepin = '',$error_code ='',$trace_id = ''){
        if($this->Admin_user2_model->Pin_pay($in_agent_id,$in_kdepin)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'pin invalid', 'sn' => array(
                'sn' => ''));
        $this->logAction('response', $trace_id, $data, 'failed,pin invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_kdpin_data($in_agent_id = null, $in_kdepin = '',$error_code ='',$trace_id = ''){
        if($this->Admin_user2_model->Pin_pay($in_agent_id,$in_kdepin)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'pin invalid', 'data' => '');
        $this->logAction('response', $trace_id, $data, 'failed,pin invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_agent($in_agent_id = null,$error_code,$trace_id = ''){
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'sn' => '', 'code' => '');
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_agent_topup($in_agent_id = null,$error_code,$trace_id = ''){
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'sn' => '', 'code' => '');
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_agent_data($in_agent_id = null,$error_code,$trace_id = '',$data = ''){
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'data' => $data);
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_prm($var = '',$errorcode = '',$trace_id = '',$field = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'sn' => '');
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);
            
        }
    }
    protected function _Check_prm_data($var = '',$errorcode = '',$trace_id = '',$field = '',$data = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);
            
        }
    }
    protected function _Check_prm_topup($var = '',$errorcode = '',$trace_id = '',$field = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'sn' =>'','code' => '');
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);
            
        }
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    function Hitget($url = '')    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }   
    protected function Rp($value = null)    {
        return number_format($value,0,",",".");
    }
}

define('PULSA_CODE', '227');
define('PULSA_MYCODE', '200');
define('PLN_TOKEN_CODE', '229');
define('PLN_POSTPAID_CODE', '230');
define('MFINANCE_CODE', '235');
define('MFINANCE_MYCODE', '200');
define('PLN_TOKEN_MYCODE', '200');
define('PLN_POSTPAID_MYCODE', '200');
define('KODE_KANTOR_DEFAULT', '35');
define('KODE_PIN_DEFAULT', '1234');
define('ADM_DEFAULT', '0.00');
define('KODEPERKKAS_DEFAULT', '406');
define('PRODUCT_CODE_POSPAID', '5002');
define('PROVIDER_CODE_POSPAID', '115');
define('PROVIDER_CODE_FINANCE', '113');
//define('URLPULSA', 'http://202.43.173.13/pulsa_gateway/topup.php');
define('URLPULSA', 'http://10.1.1.62/API_BMT_NEW/topup_pulsa.php');
//define('URLPULSA', 'http://202.43.173.13/API_BMT_NEW/topup_pulsa.php');
define('URLPLN', 'http://10.1.1.62/API_BMT_NEW/tokenpln.php');
//define('URLPLN_POSTPAID_INQ', 'http://202.43.173.13/API_BMT_NEW/7_request_inquiry_plnpostpaid.php');
define('URLPLN_POSTPAID_INQ', 'http://10.1.1.62/API_BMT_NEW/7_request_inquiry_plnpostpaid.php');
//define('URLPLN_POSTPAID_PAY', 'http://202.43.173.13/API_BMT_NEW/7_request_payment_plnpostpaid.php');
define('URLPLN_POSTPAID_PAY', 'http://10.1.1.62/API_BMT_NEW/7_request_payment_plnpostpaid.php');
define('URL_MFINANCE_INQ', 'http://10.1.1.62/API_BMT_NEW/8_request_inquiry_multifinance.php');
//define('URL_MFINANCE_INQ', 'http://202.43.173.13/API_BMT_NEW/8_request_inquiry_multifinance.php');
//define('URL_MFINANCE_PAY', 'http://202.43.173.13/API_BMT_NEW/8_request_payment_multifinance.php');
define('URL_MFINANCE_PAY', 'http://10.1.1.62/API_BMT_NEW/8_request_payment_multifinance.php');