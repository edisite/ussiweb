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
    var $in_rkning , $topup_periode1;
    private $arr_data = array();
    private $arr_res = array();
    
    private $mn_cash_awal   = 0;
    private $mn_cash_akhir  = 0;
    private $mn_cash_adm    = 0;
    
    private $mn_debt_awal   = 0;
    private $mn_debt_akhir  = 0;
    private $mn_debt_adm    = 0;
    
    private $biaya_adm      = 0;
    
    private $biaya_adm_indo = 0;
    private $biaya_adm_bmt  = 0;
    private $biaya_adm_agen = 0;
    private $jml_periode_pln_post = 1;
    
    private $res_result = array(
            'meterid'       => '',
            'cust_name'     => '',
            'daya'          => '',
            'periode'       => '',
            'periode2'      => '',
            'periode3'      => '',
            'periode4'      => '',
            'stand_meter'   => '',
            'tagihan'       => '',
            'adm'           => '',            
            'total'         => '',            
            'code'          => ''            
        );
    private $res_token = array('token' => '', 'nama' => '', 'tarif_daya' => '','jml_kwh' => '','nominal' => '','jml_bayar' => '');
            
    public $arr_logcommerce = [
            'dtm'           => '',
            'nomorid'       => '',
            'product'       => '',
            'kategori'      => '',
            'price'         => '',
            'sn'            => '',
            'nama'          => '',
            'tarif_daya'    => '',
            'jml_kwh'       => '',
        ];
    
    public $arr_logpayment_pln = [
            'dtm'           => '',
            'nomorid'       => '',
            'custname'      => '',
            'kategori'      => '',
            'periode1'      => '',
            'periode2'      => '',
            'periode3'      => '',
            'periode4'      => '',
            'tagihan'       => '',
            'adm'           => '',
            'total_bayar'   => '',
            'sn'            => ''
        ];
    public $arr_logpayment_bpjs = [
            'dtm'           => '',
            'nomorid'       => '',
            'custname'      => '',
            'kategori'      => '',
            'periode'       => '',
            'totalperson'   => '',
            'no_reff'       => '',
            'tagihan'       => '',
            'adm'           => '',
            'total_bayar'   => '',
            'sn'            => ''
        ];
    private  $res_bpjs_inq = [
            'bpjsid'        => '',
            'cust_name'     => '',
            'periode'       => '',
            'total_person'  => '',
            'no_reff'       => '',
            'tagihan'       => '',
            'adm'           => '',            
            'total'         => '',            
            'code'          => ''            
        ];
    
    private $topup_statushit = 0;
    
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
            $this->logAction('response', $trace_id, $data, '');;
            $this->response($data);
        }
        $res_list = $this->Pay_model->List_op(strtolower($in_provider));
        $this->logAction('select', $trace_id,array(), 'Pay_model->Listpulsa("'.$in_provider.'")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
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
        
        $this->_Check_prm_data($no_meter,'611', $trace_id, 'meterid isempty',  $this->res_result);
        $this->_Check_prm_data($no_meter,'613', $trace_id, 'agentid isempty',  $this->res_result);
        if (!is_numeric($no_meter)){
            $data = array('status' => FALSE,'error_code' => '612','message' => 'meterid invalid','data' => $this->res_result);
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$in_msisdn.']');
            $this->response($data);    
        }
        $this->_Check_agent_data($in_agenid, '614', $trace_id,  $this->res_result);
        
        $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_POSPAID);
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid          = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
//                $this->biaya_adm_indo     = $val_postpaid->price_original  ? $val_postpaid->price_original : 0;
//                $this->biaya_adm_bmt      = $val_postpaid->price_stok  ? $val_postpaid->price_stok : 0;
//                $this->biaya_adm_agen     = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
//                $this->biaya_adm          = $val_postpaid->adm_payment_total  ? $val_postpaid->adm_payment_total : 0;
//                
                $this->biaya_adm_indo     = $val_postpaid->price_original  ? $val_postpaid->price_original : 0;
                $this->biaya_adm_bmt      = $val_postpaid->price_stok  ? $val_postpaid->price_stok : 0;
                $this->biaya_adm_agen     = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
                $this->biaya_adm_prov     = $val_postpaid->adm_payment_provider  ? $val_postpaid->adm_payment_provider : 0;
            }
           // $this->biaya_adm = $this->biaya_adm_indo + $this->biaya_adm_bmt + $this->biaya_adm_agen;
            
              
        }else{
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_result);
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
        //$res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"17121387106994553432","cust_id":"525060838874","cust_name":"TATI SURIYAH","rec_payable":"1","rec_rest":[],"reffno_pln":"17CD94917002","unit_svc":[],"svc_contact":[],"tariff":[],"daya":[],"admin_fee":"1600","periode1":"201712","periode2":["      "],"periode3":["      "],"periode4":["      "],"duedate":[],"mtr_date":[],"bill_amount":"24852","insentif":[],"ppn":[],"penalty":[],"lwbp_last":"00007851","lwbp_crr":"00007915","wbp_last":[],"wbp_crr":[],"kvarh_last":[],"kvarh_crr":[],"message":"Berhasil","provider":"Mitracomm"}';
        
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_result);
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
            $this->biaya_adm        = isset($data_topup->admin_fee) ? $data_topup->admin_fee : 0;
            $this->topup_periode1   = isset($data_topup->periode1) ? $data_topup->periode1 : NULL;
            $topup_periode2         = isset($data_topup->periode2) ? $data_topup->periode2 : NULL;
            $topup_periode3         = isset($data_topup->periode3) ? $data_topup->periode3 : NULL;
            $topup_periode4         = isset($data_topup->periode4) ? $data_topup->periode4 : NULL;
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
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid product','data' => $this->res_result);          
            }elseif($topup_respon_code == '10' || $topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '603','message' => 'invalid product','data' => $this->res_result);       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '601','message' => 'tagihan lunas','data' => $this->res_result);
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_result);
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_result);
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '602','message' => 'saldo insufficent balance','data' => $this->res_result);
            }else{
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_result);
            }            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        } 
        if(!$this->Tab_model->Ins_Tbl('com_pln_ses',$data_topup)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $this->res_result);
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
        }
        
        $this->jml_periode_pln_post = $topup_rec_payable;
        $this->biaya_adm_indo   = $this->biaya_adm_indo * $this->jml_periode_pln_post;
        $this->biaya_adm_bmt    = $this->biaya_adm_bmt * $this->jml_periode_pln_post;
        $this->biaya_adm_agen   = $this->biaya_adm_agen * $this->jml_periode_pln_post;
        $this->biaya_adm_prov   = $this->biaya_adm_prov * $this->jml_periode_pln_post;

        $this->biaya_adm        = $this->biaya_adm_indo + $this->biaya_adm_bmt + $this->biaya_adm_agen + $this->biaya_adm_prov;

        $this->tot_tagihan  = $topup_bill_amount;
        $this->total        = $this->tot_tagihan + $this->biaya_adm;
        //$this->logAction('data', $trace_id, array(), 'tagihan: '.$this->tot_tagihan.' + admin: '.$biaya_adm.' = '.);
        $arr_upd = array(
            'log_trace'     => $trace_id,
            'tagihan'       => $this->tot_tagihan,
            'biaya_adm'     => $this->biaya_adm,
            'total'         => $this->total
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
            'periode'       => $this->topup_periode1,
            'periode2'      => $topup_periode2,
            'periode3'      => $topup_periode3,
            'periode4'      => $topup_periode4,
            'stand_meter'   => '',
            'tagihan'       => $this->Rp($this->tot_tagihan),
            'adm'           => $this->Rp($this->biaya_adm),            
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
        $this->logAction('info', $trace_id, array(),' - msisdn['.$in_msisdn.']');
        $in_msisdn = $this->Msisdn_filter($in_msisdn);
        $this->logAction('info', $trace_id, array(), 'msisdn filter['.$in_msisdn.']');
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
                    $agn_norekening     = $sub_call_user_agent->no_rekening;
                    $agn_active         = $sub_call_user_agent->active;
                    $agn_username       = $sub_call_user_agent->username;
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
        //var_dump($res_product);
        foreach ($res_product as $sub_product) {
            $r_provider             = $sub_product->provider;
            $r_kode_produk          = $sub_product->product_id;
            $r_type                 = $sub_product->type;
            $r_price_original       = $sub_product->price_original;
            $r_price_stok           = $sub_product->price_stok;
            $r_price_selling        = $sub_product->price_selling;
            $r_product_alias        = $sub_product->product_alias;
            $r_kode_integrasi       = $sub_product->kode_integrasi;
        }
        
        //**** cek nasabah
        if(strtolower($in_paytype) == "nasabah"){
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

            if(floatval(round($r_price_selling)) > round($cek_saldo_akhir)){
                $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
                $this->response($data);
            }
            //get rekening indosis
            $get_nomor_rekening_partner = $this->Tab_model->Acc_by_partnerid('INDOSIS_PRODUCTION');
            $this->logAction('transaction', $trace_id, array(), 'get rekening partner :Tab_model->Acc_by_partnerid(INDOSIS_PRODUCTION)');
            if(empty($get_nomor_rekening_partner)){
                $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed');
                $this->response($data);
                
            }
            $this->logAction('transaction', $trace_id, array(), 'rek partner : '.$get_nomor_rekening_partner);
            
        }
        
        //*****************************************
        //lokasi hit ke server pulsa
        //*****************************************
        $URL = URLPULSA;
        $URL = $URL."?no_hp=".$in_msisdn;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);
        $URL = $URL."&jumlah=".rawurlencode($r_product_alias);
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        
        $this->CTStart();
        
        $res_call_hit_topup = $this->Hitget($URL);
        //$res_call_hit_topup = '{"status":"OK","rc":"00","kode_produk":"SN5","trx_id":"628551000727","saldo":"5273323","harga":"5675","no_hp":"081319292741","jumlah":"5000","message":"SN=8040215515021180780;02\/04\/18 16:14 ISI HS5T KE 081319292741, SUKSES. SAL=5.273.323,HRG=5.675,ID=3314742,SN=8040215515021180780; BESTRELOAD","sn":"8040215515021180780","provider":"sunreload"}';
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        //sleep(10);
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->rc) ? $data_topup->rc : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_harga            = isset($data_topup->harga) ? $data_topup->harga : NULL;
        $topup_no_serial        = isset($data_topup->sn) ? $data_topup->sn : '';
        $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
        
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            //return;
        }
        //02042018
        //request cek harga indosis 
        //if harga mitra > harga indosis
        if(floatval(round($topup_harga)) > round($r_price_original)){                
                $this->logAction('response', $trace_id, array(), 'harga mitra :'. $this->Rp($topup_harga). ' - harga indosis : '.$this->Rp($r_price_original));
                $r_price_original = $topup_harga;                
        }
        //
        
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
            'desc_trx'      => 'hit topup 3party',
            'provider'      => $r_provider,
            'price_selling' => $r_price_selling,
            'price_stok'    => $r_price_stok,
            'price_original'=> $r_price_original,
            'price_fr_mitra'=> $topup_harga,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'     => $trace_id,
            'provider'      => $topup_provider
        );
        
        $this->logAction('insert', $trace_id, $com_pulsa_log, 'Tab_model->Ins_Tbl("com_pulsa_log")');
        
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
            $this->logAction('insert', $trace_id, array(), 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
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
                if(empty($topup_message)){
                    $topup_message = "Double Transaksi";
                }
                //tambah status 635 201711291145
                $data = array('status' => FALSE,'error_code' => '635','message' => $topup_message,'sn' => $topup_no_serial,'code' => '');
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            }             
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }        
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_msisdn." ".  $this->Rp($r_price_selling)." SN:.".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(),
            'NOCUSTOMER'        =>$in_msisdn,
            'COMTYPE'           =>$r_type,
//            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$res_code_kode,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'MODEL'             =>  $this->model_com,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
        
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
        if(!$res_ins){
           $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
           $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
           $this->response($data);
        }        
        
        if(strtolower($in_paytype) == 'nasabah'){
            //debit nasabah
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_TABTRANS_ID,'','TARIK');
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_msisdn,$in_agenid,$r_price_selling,PULSA_CODE,PULSA_MYCODE,$gen_id_TABTRANS_ID,'','TARIK');
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID,'','SETOR');
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_msisdn,$in_agenid,$r_price_selling,PULSA_INDOSIS_CODE,PULSA_INDOSIS_MYCODE,$gen_id_TABTRANS_ID,'','SETOR');
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }            
        }
        
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_msisdn." Rp".$this->Rp($r_price_selling)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PULSA BERHASIL",$gen_message);
           
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }        
            //
            $in_addpoin = array(
                'tid' 	=> $gen_id_TABTRANS_ID,
                'agent' => $in_agenid,
                'kode' 	=> $res_mycode_kode,
                'jenis' => 'COM',
                'nilai' => $r_price_selling
                );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
//            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
//            if($res_poin){
//                $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
//            }else{
//                $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
//            }        
        //
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code'=> $trace_id);
            //$dataupd = array('master_id' => $gen_id_TABTRANS_ID);
            $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
                   
    } 
    public function Pulsa_sms_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        //$in_agenid = $this->input->post('agentid') ? $this->input->post('agentid') : ''; //**
        $in_msisdn = $this->input->post('msisdn') ? $this->input->post('msisdn') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : 'agent'; // value of agent or nasabah[90]
        $in_produk = $this->input->post('produk') ? $this->input->post('produk') : '';        
        $in_kdepin = $this->input->post('pin') ? $this->input->post('pin') : '9999';       //**
        $in_dest = $this->input->post('dest_agent') ? $this->input->post('dest_agent') : '';       //**
        
        $this->_Check_prm_topup($in_dest,'611',$trace_id, 'nodest agent isempty');   //***
        //$this->_Check_prm_topup($in_agenid,'612',$trace_id, 'in_agenid isempty');   //***
        $this->_Check_prm_topup($in_msisdn,'614',$trace_id, 'msisdn isempty');    // cek parameter
        $this->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty');    // -
        $this->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin isempty');       //
        //$this->_Check_agent($in_agenid,'621',$trace_id);            //***
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
        if(strlen($in_kdepin) > 4 || strlen($in_kdepin) < 4){
            $data = array('status' => FALSE,'error_code' => '616','message' => 'pid invalid','sn' => '','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, pin lenght ['.strlen($in_kdepin).']');
            $this->response($data);
        }
        $in_msisdn = $this->Msisdn_filter($in_msisdn);
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('no_rekening') ?: '';
            $this->_Check_prm($in_rkning,'613',$trace_id, 'rekening isempty');   //***            
        }elseif(strtolower($in_paytype) == "agent"){
            $res_call_user_agent = $this->Admin_user2_model->User_by_msisdn_pin($in_dest,$in_kdepin);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_msisdn_pin ['.$in_dest.','.$in_kdepin.']');
            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening     = $sub_call_user_agent->no_rekening;
                    $agn_active         = $sub_call_user_agent->active;
                    $agn_username       = $sub_call_user_agent->username;
                    $in_agenid          = $sub_call_user_agent->id;
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
        //var_dump($res_product);
        foreach ($res_product as $sub_product) {
            $r_provider             = $sub_product->provider;
            $r_kode_produk          = $sub_product->product_id;
            $r_type                 = $sub_product->type;
            $r_price_original       = $sub_product->price_original;
            $r_price_stok       = $sub_product->price_stok;
            $r_price_selling        = $sub_product->price_selling;
            $r_product_alias        = $sub_product->product_alias;
            $r_kode_integrasi       = $sub_product->kode_integrasi;
        }
        
        //**** cek nasabah
        if(strtolower($in_paytype) == "nasabah"){
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
        //sleep(5);
        $res_call_hit_topup = $this->Hitget($URL);
        //$res_call_hit_topup = '{"status":"OK","rc":"00","kode_produk":"SN5","trx_id":"628551000727","saldo":"","no_hp":"081319292741","jumlah":"","message":"16\/05\/17 17:22 ISI SN5 KE 081319292741, Transaksi anda SDH DITERIMA dan sedang DIPROSES, PENDING OPERATOR mhn di tunggu SISA. SAL=356.990,HRG=5.950,ID=60006804, INFO : Perubahan Harga TSEL 5 utk RS silahkan cek harga , thx.","sn":"92792842942379292472392","provider":"Powermedia"}';
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
        $topup_harga            = isset($data_topup->harga) ? $data_topup->harga : NULL;
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
            'desc_trx'      => 'hit topup 3party',
            'provider'      => $r_provider,
            'price_selling' => $r_price_selling,
            'price_stok'    => $r_price_stok,
            'price_original'=> $r_price_original,
            'price_fr_mitra'=> $topup_harga,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'     => $trace_id,
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
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_msisdn." ".  $this->Rp($r_price_selling)." SN:.".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(),
            'NOCUSTOMER'        =>$in_msisdn,
            'COMTYPE'           =>$r_type,
//            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$res_code_kode,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'MODEL'             =>  $this->model_com,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );
        
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
        if(!$res_ins){
           $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
           $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
           $this->response($data);
        }
            
        if(strtolower($in_paytype) == 'nasabah'){

            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_TABTRANS_ID,'SETOR');
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_msisdn,$in_agenid,$r_price_selling,PULSA_CODE,PULSA_MYCODE,$gen_id_TABTRANS_ID,'SETOR');
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID,'TARIK');
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_msisdn,$in_agenid,$r_price_selling,PULSA_INDOSIS_CODE,PULSA_INDOSIS_MYCODE,$gen_id_TABTRANS_ID,'TARIK');
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }  
            
        }
         //
            $in_addpoin = array(
                'tid' => $gen_id_TABTRANS_ID,
                'agent' => $in_agenid,
                'kode' => $res_mycode_kode,
                'jenis' => 'COM',
                'nilai' => $r_price_selling
                );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            if($res_poin){
                $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
            }else{
                $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
            }
        
        //
                  
        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code'=> $trace_id);
        $dataupd = array('master_id' => $gen_id_TABTRANS_ID);
        $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
        $this->logAction('result', $trace_id, array(), 'success, running trasaction');
        $this->logAction('update', $trace_id, array(), 'Update log pulsa');

        $this->logAction('response', $trace_id, $data, '');
        $this->response($data);
            
                  
    }
    public function Pln_token_sms_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        //$in_agenid      = $this->input->post('agentid') ?: ''; //**
        $in_pln         = $this->input->post('plnid') ?: '';  // post data
        $in_paytype     = $this->input->post('paytype') ?: 'agent'; // value of agent or nasabah[90]
        $in_produk      = $this->input->post('produk') ?: '';        
        $in_kdepin      = $this->input->post('pin') ?: '';       //**
        $in_dest        = $this->input->post('dest_agent') ? $this->input->post('dest_agent') : '';       //**        
        $this->_Check_prm_topup($in_dest,'611',$trace_id, 'nodest agent isempty');   //***
        
        //$this->_Check_prm_topup($in_agenid,'612',$trace_id, 'in_agenid isempty');   //***
        $this->_Check_prm_topup($in_pln,'614',$trace_id, 'plnid isempty');    // cek parameter
        $this->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty');    // -
        $this->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin isempty');       //
        //$this->_Check_agent_topup($in_agenid,'621',$trace_id);            //***
        if(strlen($in_kdepin) > 4 || strlen($in_kdepin) < 4){
            $data = array('status' => FALSE,'error_code' => '616','message' => 'pid invalid','sn' => '','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, pin lenght ['.strlen($in_kdepin).']');
            $this->response($data);
        }
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
            $in_rkning = $this->input->post('no_rekening');
            $this->_Check_prm($in_rkning,'613',$trace_id, 'rekening isempty');   //***            
        }elseif(strtolower($in_paytype) == "agent"){
            //$res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agenid);
            $res_call_user_agent = $this->Admin_user2_model->User_by_msisdn_pin($in_dest,$in_kdepin);
            $this->logAction('check', $trace_id, array(), 'Admin_user2_model->User_by_msisdn_pin ['.$in_dest.','.$in_kdepin.']');            
            if($res_call_user_agent){
                $this->logAction('result', $trace_id,$res_call_user_agent , 'OK');
                foreach ($res_call_user_agent as $sub_call_user_agent) {
                    $agn_norekening     = $sub_call_user_agent->no_rekening;
                    $agn_active         = $sub_call_user_agent->active;
                    $agn_username       = $sub_call_user_agent->username;
                    $in_agenid          = $sub_call_user_agent->id;
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
            $r_provider             = $sub_product->provider;
            $r_kode_produk          = $sub_product->product_id;
            $r_type                 = $sub_product->type;
            $r_price_original       = $sub_product->price_original;
            $r_price_stok           = $sub_product->price_stok;
            $r_price_selling        = $sub_product->price_selling;
            $r_product_alias        = $sub_product->product_alias;
            $r_kode_integrasi       = $sub_product->kode_integrasi; //revisi
        }
        
        //**** cek nasabah
        if(strtolower($in_paytype) == "nasabah"){ //revisi
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
            $get_nomor_rekening_partner = $this->Tab_model->Acc_by_partnerid('INDOSIS_PRODUCTION');
            $this->logAction('transaction', $trace_id, array(), 'get rekening partner :Tab_model->Acc_by_partnerid(INDOSIS_PRODUCTION)');
            if(empty($get_nomor_rekening_partner)){
                $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed');
                $this->response($data);
                
            }
            $this->logAction('transaction', $trace_id, array(), 'rek partner : '.$get_nomor_rekening_partner);
            
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
        //$res_call_hit_topup = '{"status":"OK","respon_code":"00","request_id":"628551000727","message":"SN=3561-2601-7730-8936-8010\/JOHNNYWENNYWEOL\/R1\/1300VA\/13,40;26\/01\/17 14:27 ISI PLN20 KE 01108490556, BERHASIL SISA. SAL=112.800,HRG=19.860,ID=57693947,SN=3561-2601-7730-8936-8010\/JOHNNYWENNYWEOL\/R1\/1300VA\/13,40; INFO : XL, AXIS, ISAT DATA untuk RS silahkan cek harga , thx.","kode_token":"3561-2601-7730-8936-8010\/JOHNNYWENNYWEOL\/R1\/1300VA\/13","saldo":"112800","id":"57693947","provider":"Powermedia"}';
        //sleep(12);
        
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        if(!$this->is_json($res_call_hit_topup)){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            $this->logAction('result', $trace_id, $data, 'failed, response error format detect not json');
            $this->response($data);
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->respon_code) ? $data_topup->respon_code : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_harga            = isset($data_topup->harga) ? $data_topup->harga : NULL;
        $topup_no_serial        = isset($data_topup->kode_token) ? $data_topup->kode_token : '';
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
            'price_stok'    => $r_price_stok,
            'price_original'=> $r_price_original,
            'price_fr_mitra'=> $topup_harga,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'     => $trace_id,
            'provider'      => $topup_provider
        );
        
        
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' =>'','code'=>'');
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            return;
        }
        if((strtolower($topup_respon_code) == '00') 
            || (strtolower($topup_respon_code) == '68')){            
        }else{
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
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            }elseif($topup_respon_code == '94'){
                if(empty($topup_message)){
                    $topup_message = "Double Transaksi";
                }
                //tambah status 635 201711291145
                $data = array('status' => FALSE,'error_code' => '635','message' => $topup_message,'sn' => $topup_no_serial,'code'=>'');
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }     
        //revisi start
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
        
        //revisi end
        $gen_id_COMTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();        
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_pln." ".  $this->Rp($r_price_selling)." SN:92372892749748";       
        $in_KUITANSI    = $this->_Kuitansi();                    
        //revisi
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_COMTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NOCUSTOMER'        =>$in_pln, 
            'COMTYPE'           =>$r_type,
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$res_code_kode,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'MODEL'             =>  $this->model_com,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );

        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }   
        //revisi start        
        if(strtolower($in_paytype) == 'nasabah'){
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_pln.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_COMTRANS_ID,'SETOR');
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_pln,$in_agenid,$r_price_selling,PLN_TOKEN_CODE,PLN_TOKEN_MYCODE,$gen_id_COMTRANS_ID,'SETOR');
            
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_pln.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_COMTRANS_ID,'TARIK');
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_pln,$in_agenid,$r_price_selling,PLN_INDOSIS_CODE,PLN_INDOSIS_MYCODE,$gen_id_COMTRANS_ID,'TARIK');
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            
        }
            
        //
            $in_addpoin = array(
                'tid' => $gen_id_COMTRANS_ID,
                'agent' => $in_agenid,
                'kode' => $res_mycode_kode,
                'jenis' => 'COM',
                'nilai' => $r_price_selling
                );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            if($res_poin){
                $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
            }else{
                $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
            }

            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code' => $trace_id);
            $dataupd = array('master_id' => $gen_id_COMTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
                 
    }
    public function Topup_pln_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid      = $this->input->post('agentid') ?: ''; //**
        $in_pln         = $this->input->post('plnid') ?: '';  // post data
        $in_paytype     = $this->input->post('paytype') ?: ''; // value of agent or nasabah[90]
        $in_produk      = $this->input->post('produk') ?: '';        
        $in_kdepin      = $this->input->post('pin') ?: '9999';       //**
        
        
        $this->_Check_prm_topup($in_agenid,'612',$trace_id, 'in_agenid isempty',  $this->res_token);   //***
        $this->_Check_prm_topup($in_pln,'614',$trace_id, 'plnid isempty',$this->res_token);    // cek parameter
        $this->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty',$this->res_token);    // -
        $this->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin isempty',$this->res_token);       //
        $this->_Check_agent_topup($in_agenid,'621',$trace_id,$this->res_token);            //***
        //$this->_Check_kdpin($in_agenid, $in_kdepin,'622',$trace_id); // pin
        if (strlen($in_pln) < 8)
	{            
            $data = array('status' => FALSE,'error_code' => '623','message' => 'plnid invalid','sn' => '','code'=>'','data' => $this->token);
            $this->logAction('response', $trace_id, $data, 'failed, plnid lenght ['.strlen($in_pln).']');
            $this->response($data);  
        }
        if (!is_numeric($in_pln))
	{
            $data = array('status' => FALSE,'error_code' => '623','message' => 'plnid invalid','sn' => '','code'=>'','data' => $this->token);
            $this->logAction('response', $trace_id, $data, 'failed, plnid isnot numeric ['.$in_pln.']');
            $this->response($data);    
        }
        $this->logAction('info', $trace_id, array(), 'paytype by ['.$in_paytype.']');
        if(strtolower($in_paytype) == "nasabah"){
            $in_rkning = $this->input->post('no_rekening');
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
                    $data = array('status' => FALSE,'error_code' => '628','message' => 'account agent inactive', 'sn' =>'','code'=>'','data' => $this->token);
                    $this->logAction('response', $trace_id, $data, 'account agent inactive');
                    $this->response($data); 
                }
                $in_rkning =  $agn_norekening;
            }else{
                $data = array('status' => FALSE,'error_code' => '625','message' => 'norekening invalid', 'sn' =>'','code'=>'','data' => $this->token);
                $this->logAction('response', $trace_id, $data, 'failed, no response or empty data');
                $this->response($data); 
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '617','message' => 'paytype invalid','sn'=>'','code'=>'','data' => $this->token);
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Pulsa_by_product($in_produk,'PLN');
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '624','message' => 'produk close','sn' =>'','code'=>'','data' => $this->token);
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Pulsa_by_product('.$in_produk.',PULSA)');
            $this->response($data);    
        }
        foreach ($res_product as $sub_product) {
            $r_provider             = $sub_product->provider;
            $r_kode_produk          = $sub_product->product_id;
            $r_type                 = $sub_product->type;
            $r_price_original       = $sub_product->price_original;
            $r_price_stok           = $sub_product->price_stok;
            $r_price_selling        = $sub_product->price_selling;
            $r_product_alias        = $sub_product->product_alias;
            $r_kode_integrasi       = $sub_product->kode_integrasi; //revisi
        }
        
        //**** cek nasabah
        if(strtolower($in_paytype) == "nasabah"){ //revisi
            $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
            if(!$res_cek_rek){            
                $data = array('status' => FALSE,'error_code' => '625','message' => 'rekening invalid','sn' =>'','code'=>'','data' => $this->token);
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
            $get_nomor_rekening_partner = $this->Tab_model->Acc_by_partnerid('INDOSIS_PRODUCTION');
            $this->logAction('transaction', $trace_id, array(), 'get rekening partner :Tab_model->Acc_by_partnerid(INDOSIS_PRODUCTION)');
            if(empty($get_nomor_rekening_partner)){
                $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '','data' => $this->token);
                $this->logAction('response', $trace_id, $data, 'failed');
                $this->response($data);
                
            }
            $this->logAction('transaction', $trace_id, array(), 'rek partner : '.$get_nomor_rekening_partner);
            
        }
        $this->CTStart();
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URLPLN;
        $URL = $URL."?id_pln=".$in_pln;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);
        //$URL = $URL."&jumlah=".$r_product_alias;
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        //$res_call_hit_topup = '{"status":"OK","rc":"00","kode_produk":"SN5","trx_id":"628551000727","saldo":"5273323","harga":"5675","no_hp":"081319292741","jumlah":"5000","message":"SN=8040215515021180780;02\/04\/18 16:14 ISI HS5T KE 081319292741, SUKSES. SAL=5.273.323,HRG=5.675,ID=3314742,SN=8040215515021180780; BESTRELOAD","sn":"8040215515021180780","provider":"sunreload"}';
        //sleep(12);
        
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'','data' => $this->token);
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        if(!$this->is_json($res_call_hit_topup)){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'','data' => $this->token);
            $this->logAction('result', $trace_id, $data, 'failed, response error format detect not json');
            $this->response($data);
        }
        $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
        $data_topup = json_decode($res_call_hit_topup);
        
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->respon_code) ? $data_topup->respon_code : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_harga            = isset($data_topup->harga) ? $data_topup->harga : NULL;
        $topup_no_serial        = isset($data_topup->kode_token) ? $data_topup->kode_token : '';
        $topup_transid          = isset($data_topup->id) ? $data_topup->id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'','data' => $this->token);
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            //return;
        }
        //"kode_token":"3199-2792-4713-1396-0083/CAHYONO/R1M/900/13,6",
        list($get_token, $get_nama, $get_tarif, $get_daya, $get_jml_kwh) = explode("/", $topup_no_serial);
        
        //02042018
        //request cek harga indosis 
        //if harga mitra > harga indosis
        if(floatval(round($topup_harga)) > round($r_price_original)){                
                $this->logAction('response', $trace_id, array(), 'harga mitra :'. $this->Rp($topup_harga). ' - harga indosis : '.$this->Rp($r_price_original));
                $r_price_original = $topup_harga;                
        }
        //
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'msisdn'        => $in_pln,
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
            'price_stok'    => $r_price_stok,
            'price_original'=> $r_price_original,
            'price_fr_mitra'=> $topup_harga,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'     => $trace_id,
            'provider'      => $topup_provider
        );
        
        
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' =>'','code'=>'','data' => $this->token);
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            return;
        }
        if((strtolower($topup_respon_code) == '00') 
            || (strtolower($topup_respon_code) == '68')){            
        }else{
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '622','message' => 'invalid pin','sn' =>'','code'=>'','data' => $this->token);                
            }elseif($topup_respon_code == '04'){
                $data = array('status' => FALSE,'error_code' => '624','message' => 'invalid product','sn' =>'','code'=>'','data' => $this->token);          
            }elseif($topup_respon_code == '10'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'invalid plnid','sn' =>'','code'=>'','data' => $this->token);       
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '89'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '99'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '626','message' => 'saldo tabungan tidak cukup','sn' => '','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '06'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '63'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '30'){
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'','data' => $this->token);
            }elseif($topup_respon_code == '94'){
                if(empty($topup_message)){
                    $topup_message = "Double Transaksi";
                }
                //tambah status 635 201711291145
                $arr_sub = array('token' => $get_token, 'nama' => $get_nama, 'tarif_daya' => $get_tarif.'/'.$get_daya,'jml_kwh' => $get_jml_kwh,'nominal' => $r_product_alias,'jml_bayar' => $this->Rp($r_price_selling));
            
                $data = array('status' => FALSE,'error_code' => '635','message' => $topup_message,'sn' => $topup_no_serial,'code'=> $trace_id,'data' => $arr_sub);
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code'=>'');
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
        }     
        //revisi start
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
        
        //revisi end
        $gen_id_COMTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();        
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_pln." ".  $this->Rp($r_price_selling)." SN:92372892749748";       
        $in_KUITANSI    = $this->_Kuitansi();                    
        //revisi
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_COMTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NOCUSTOMER'        =>$in_pln, 
            'COMTYPE'           =>$r_type,
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$r_price_selling,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$res_code_kode,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'MODEL'             =>  $this->model_com,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );

        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code'=>'','data' => $this->token);
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }   
        //revisi start        
        if(strtolower($in_paytype) == 'nasabah'){
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_pln.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_COMTRANS_ID,'','TARIK');
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_pln,$in_agenid,$r_price_selling,PLN_TOKEN_CODE,PLN_TOKEN_MYCODE,$gen_id_COMTRANS_ID,'','TARIK');
            
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_pln.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_COMTRANS_ID,'','SETOR');
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_pln,$in_agenid,$r_price_selling,PLN_INDOSIS_CODE,PLN_INDOSIS_MYCODE,$gen_id_COMTRANS_ID,'','SETOR');
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            
        }
            
        //
            $in_addpoin = array(
                'tid' => $gen_id_COMTRANS_ID,
                'agent' => $in_agenid,
                'kode' => $res_mycode_kode,
                'jenis' => 'COM',
                'nilai' => $r_price_selling
                );
            $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
            $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
            if($res_poin){
                $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
            }else{
                $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
            }
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_pln." Rp".$this->Rp($r_price_selling)." SN:".$topup_no_serial." ID : ".$gen_id_COMTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PLN TOKEN BERHASIL",$gen_message);          
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }
        
        //
        // end
        
            //list($get_token, $get_nama, $get_tarif, $get_daya, $get_jml_kwh) = explode("/", $topup_no_serial);
        
            $arr_sub = array('token' => $get_token, 'nama' => $get_nama, 'tarif_daya' => $get_tarif.'/'.$get_daya,'jml_kwh' => $get_jml_kwh,'nominal' => $r_product_alias,'jml_bayar' => $this->Rp($r_price_selling));
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $get_token,'code' => $trace_id,'data' => $arr_sub);
            $dataupd = array('master_id' => $gen_id_COMTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
                 
    }
    public function Topup_pln_postpaid_pay_post() {
        $trace_id = $this->input->post('code') ?: $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('agentid') ? $this->input->post('agentid') : ''; //**
        $in_pln     = $this->input->post('meterid') ? $this->input->post('meterid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : ''; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '9999';       //**
        $in_codepln = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $this->_Check_prm_pln_pay($in_agenid,'612',$trace_id, 'agentid isempty');   //***
        $this->_Check_prm_pln_pay($in_pln,'611',$trace_id, 'meterid isempty');    // cek parameter
        $this->_Check_prm_pln_pay($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $this->_Check_prm_pln_pay($in_codepln,'614',$trace_id, 'code isempty');       //
        $this->_Check_agent_data($in_agenid,'612',$trace_id);            //***
        //$this->_Check_kdpin_data($in_agenid, $in_kdepin,'615',$trace_id); // pin
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
            $this->topup_statushit        = isset($sub_product->status_hit) ? $sub_product->status_hit : 0;
        }
        
        if($this->topup_statushit > 1){
            $this->logAction('info', $trace_id, $data, 'topup_statushit ('.$this->topup_statushit.')');
            $data = array('status' => FALSE,'error_code' => '622','message' => 'Session Expired, Ulangi lagi!','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Code sudah pernah digunakan, Session Expired -- ('.$in_codepln.','.$in_pln.')');
            $this->response($data); 
        }    
            
        $this->Pay_model->Upd_pln_ses_by_code($trace_id,$in_pln,array('status_hit' => $this->topup_statushit + 1));
        
        //
         $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_POSPAID);
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid          = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                $this->biaya_adm_indo     = $val_postpaid->price_original  ? $val_postpaid->price_original : 0;
                $this->biaya_adm_bmt      = $val_postpaid->price_stok  ? $val_postpaid->price_stok : 0;
                $this->biaya_adm_agen     = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
                $this->biaya_adm_prov     = $val_postpaid->adm_payment_provider  ? $val_postpaid->adm_payment_provider : 0;
            }                      
           
        }else{
           $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
           $this->logAction('response', $trace_id, $data, 'failed');
           $this->response($data);
        }
        
        $this->jml_periode_pln_post = $topup_rec_payable;
        $this->biaya_adm_indo   = $this->biaya_adm_indo * $this->jml_periode_pln_post;
        $this->biaya_adm_bmt    = $this->biaya_adm_bmt * $this->jml_periode_pln_post;
        $this->biaya_adm_agen   = $this->biaya_adm_agen * $this->jml_periode_pln_post;
        $this->biaya_adm_prov   = $this->biaya_adm_prov * $this->jml_periode_pln_post;
        
        $this->biaya_adm        = $this->biaya_adm_indo + $this->biaya_adm_bmt + $this->biaya_adm_agen + $this->biaya_adm_prov;
        
        //**** cek nasabah
        if(strtolower($in_paytype) == "nasabah"){
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
            //get rekening indosis
            $get_nomor_rekening_partner = $this->Tab_model->Acc_by_partnerid('INDOSIS_PRODUCTION');
            $this->logAction('transaction', $trace_id, array(), 'get rekening partner :Tab_model->Acc_by_partnerid(INDOSIS_PRODUCTION)');
            if(empty($get_nomor_rekening_partner)){
                $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed');
                $this->response($data);
                
            }
            $this->logAction('transaction', $trace_id, array(), 'rek partner : '.$get_nomor_rekening_partner);
            
        }
         $this->CTStart();
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
        $res_call_hit_topup = $this->Hitget($URL);
        
        //$res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"17121387106994553432","cust_id":"525060838874","cust_name":"TATI SURIYAH","rec_payable":"1","rec_rest":"reffno_pln=17CD94917002","reffno_pln":[],"unit_svc":[],"svc_contact":[],"tariff":[],"daya":[],"admin_fee":"1600","periode1":"201712","periode2":[],"periode3":[],"periode4":"Mitracomm","duedate":[],"mtr_date":[],"bill_amount":"24852","insentif":"0","ppn":"0","penalty":"0","lwbp_last":"7851","lwbp_crr":"7915","wbp_last":[],"wbp_crr":[],"kvarh_last":[],"kvarh_crr":[],"pay_datetime":"20171213094923","footer_message":"\"Informasi Hubungi Call Center 123 Atau Hub PLN Terdekat :\"","receipt_code":"0BAG210ZA98BB8C0210C64DF54B3CB81","message":"Berhasil","provider":"Mitracomm"}';
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
            'destid'        => $in_pln,
            'product'       => $topup_product_code,
            'nominal'       => 'PLN',
            'urlhit'        => $URL,
            'response'      => $res_call_hit_topup,
            'res_status'    => $topup_status,
            'res_code'      => $topup_respon_code,
            'res_message'   => $topup_message,
            'res_saldo'     => $topup_saldo,
            'userid'        => $in_agenid,
            'trx_status'    => 'payment',
            'desc_trx'      => 'last status = hit topup 3party',
            'provider'      => $topup_provider,
            'adm_provider'  => $this->biaya_adm_prov,
            'adm_indosis'   => $this->biaya_adm_indo,
            'adm_bmt'       => $this->biaya_adm_bmt,
            'adm_agen'      => $this->biaya_adm_agen,
            'adm_fr_mitra'  => $topup_admin_fee,
            'tagihan'       => $topup_tagihan,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'     => $trace_id
        );
        
        
        if(!$this->Tab_model->Ins_Tbl('com_payment_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_payment_log")');
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
        
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $this->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'COMTYPE'           =>'PLN',
            'NOCUSTOMER'        =>$in_pln,
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_tagihan,
            'ADM'               =>$topup_biaya_adm,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>$res_code_kode,
            'TOB'               =>$in_tob, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'MODEL'             =>  $this->model_pay,
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );

            
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
        if(!$res_ins){
           $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
           $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
           $this->response($data);
        }    
        if(strtolower($in_paytype) == 'nasabah'){
            //debit nasabah
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.''
                    . ','.$in_pln.','.$in_agenid.','.$topup_total.','.PLN_POSTPAID_CODE.','.PLN_POSTPAID_MYCODE.','.$gen_id_TABTRANS_ID,'','TARIK');
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_pln,$in_agenid,
                    $topup_total,PLN_POSTPAID_CODE,PLN_POSTPAID_MYCODE,$gen_id_TABTRANS_ID,'','TARIK');
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.''
                    . ','.$in_pln.','.$in_agenid.','.$topup_total.','.PLN_POSTPAID_INDOSIS_CODE.','
                    . ''.PLN_POSTPAID_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID,'','SETOR');
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_pln,
                    $in_agenid,$topup_total,PLN_POSTPAID_INDOSIS_CODE,PLN_POSTPAID_INDOSIS_MYCODE,$gen_id_TABTRANS_ID,'','SETOR');
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }            
        }

        $in_addpoin = array(
            'tid'   => $gen_id_TABTRANS_ID,
            'agent' => $in_agenid,
            'kode'  => PLN_POSTPAID_CODE,
            'jenis' => 'COM',
            'nilai' => $topup_total
        );
        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
        $this->logAction('result', $trace_id, array(), 'response');
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_pln." Rp".$this->Rp($topup_tagihan)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PLN POSTPAID BERHASIL",$gen_message);          
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }
        $arrdata = array(
            'sn' => $topup_no_serial,
            'code' => $trace_id
        );        
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
        $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
        //$this->logAction('info', $trace_id, $dataupd, 'upd');
        $this->Pay_model->Upd_logpayment($trace_id,$dataupd);          
        
        $this->logAction('result', $trace_id, array(), 'success, running trasaction');
        $this->logAction('update', $trace_id, array(), 'Update log pulsa');
        $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
        $this->logAction('response', $trace_id, $data, '');
        $this->response($data);                
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
        //$this->_Check_kdpin_data($in_agenid, $in_kdepin,'615',$trace_id); // pin
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
//            SEMENTARA DI TUTUP
            $data = array('status' => FALSE,'error_code' => '618','message' => 'paytype invalid','data'=>array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
            
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
        if(strtolower($in_paytype) == "nasabah"){
            $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
            if(!$res_cek_rek){            
                $data = array('status' => FALSE,'error_code' => '619','message' => 'rekening invalid','data' => array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
                $this->logAction('response', $trace_id, array(),'error :'.$res_cek_rek);
                $this->response($data);
            }
            foreach ($res_cek_rek as $sub_cek_rek) {
                $nama_nasabah       = $sub_cek_rek->nama_nasabah;
                $cek_nasabah_id     = $sub_cek_rek->nasabah_id;
                $cek_saldo_akhir    = $sub_cek_rek->saldo_akhir;
            }
            $this->logAction('info', $trace_id, array(), 'total biaya : '.$topup_total);
            $this->logAction('info', $trace_id, array(), 'saldo rekening : '.$cek_saldo_akhir);
            if(floatval(round($topup_total)) > round($cek_saldo_akhir)){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
                $this->response($data);

            }
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
        //$res_call_hit_topup = $this->Hitget($URL);
        
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
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $this->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $this->_Kodetrans_by_tob('100');
        }
       
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $this->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            //'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_total,
            'ADM'               =>'',
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agenid, 
            'KODE_TRANS'        =>res_code_kode,
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
            
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }
           
       if(strtolower($in_paytype) == 'nasabah'){
            $this->Tab_model->Tab_commerce($in_rkning,$in_msisdn,$in_agenid,$r_price_selling,MFINANCE_CODE,MFINANCE_MYCODE,$gen_id_TABTRANS_ID);
        } 
        $in_addpoin = array(
            'tid'   => $gen_id_TABTRANS_ID,
            'agent' => $in_agenid,
            'kode'  => $res_mycode_kode,
            'jenis' => 'TAB',
            'nilai' => $topup_total ?: 0
        );
        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
        if($res_poin){
                $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
            }else{
                $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
            }          

        $arrdata = array(
            'sn' => $topup_no_serial,
            'code' => $trace_id
        );
        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
        $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
        $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
        $this->logAction('result', $trace_id, array(), 'success, running trasaction');
        $this->logAction('update', $trace_id, array(), 'Update log pulsa');
        $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
        $this->logAction('response', $trace_id, $data, '');
        $this->response($data);
                 
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
            $this->logAction('response', $trace_id, $data, '');            
            $this->response($data);
        }elseif($g_code == "68"){
            $dta = array(
                'status_trx' => '68',
                'lastdtm' => $g_dtm,
                'sn' => $g_sn);
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->logAction('response', $trace_id, $data, ''); 
            $this->response($data);
        }elseif($g_code == ''){
            $dta = array(
                'status_trx' => '01',
                'lastdtm' => '',
                'sn' => '');
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->logAction('response', $trace_id, $data, ''); 
            $this->response($data);
        }else{
            $dta = array(
                'status_trx' => '02',
                'lastdtm' => $g_dtm,
                'sn' => $g_sn);
            $data = array('status' => TRUE,'error_code' => '000','message' => 'success','data' => $dta);
            $this->logAction('response', $trace_id, $data, ''); 
            $this->response($data);
        }        
    }
    
    public function Bpjs_inq_post() {
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $no_bpjsid = $this->input->post('bpjsid')   ? $this->input->post('bpjsid') : '';
        $in_agenid = $this->input->post('agentid')  ? $this->input->post('agentid') : '';
        $in_period = $this->input->post('periode')  ? $this->input->post('periode') : '';
        
        $this->_Check_prm_bpjs($no_bpjsid,'611', $trace_id, 'nomor bpjs isempty');
        $this->_Check_prm_bpjs($in_agenid,'612', $trace_id, 'agentid isempty');
        $this->_Check_prm_bpjs($in_period,'613', $trace_id, 'periode isempty');
        
        if (!is_numeric($no_bpjsid))	{
            $data = array('status' => FALSE,'error_code' => '614','message' => 'bpjsid invalid','data' => array('bpjsid' => '','cust_name' => '','periode' => '','tagihan' => '','adm' => '','total' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$no_bpjsid.']');
            $this->response($data);    
        }
        if($this->Admin_user2_model->User_by_id($in_agenid)){            
        }else{ $data = array('status' => FALSE,'error_code' => '612','message' => 'agentid invalid', 'data' => array('bpjsid' => '','cust_name' => '','periode' => '','tagihan' => '','adm' => '','total' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
            $this->response($data);    return;         
        }  
        
        $this->load->model('Transhoot');
        $this->logAction('info', $trace_id, array(), 'Transhoot->Bpjs_inq('.$in_agenid.','.$no_bpjsid.','.$in_period.','.$trace_id.')');
        $trans = $this->Transhoot->Bpjs_inq($in_agenid,$no_bpjsid,$in_period,$trace_id);            
        if($trans){
            $data = array('status' => TRUE,'error_code' => '600','message' => 'sucess','data' => $trans);        
            $this->LogAction('response', $trace_id, $data, '');
            $this->response($data);       
        }
//        else{
//            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('bpjsid' => '','cust_name' => '','periode' => '','tagihan' => '','adm' => '','total' => '','code' => ''));
//            $this->logAction('response', $trace_id, $data, 'failed, error response from transhoot->bpjsinquiry');
//            $this->response($data);
//        }
    }
    public function Bpjs_pay_post() {
        $trace_id = $this->input->post('code') ?: $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('agentid') ? $this->input->post('agentid') : ''; //**
        $no_bpjsid     = $this->input->post('bpjsid') ? $this->input->post('bpjsid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : ''; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '9999';       //**
        $in_codepln = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $this->_Check_prm_data($in_agenid,'612',$trace_id, 'agentid isempty');   //***
        $this->_Check_prm_data($no_bpjsid,'611',$trace_id, 'BPJSID isempty');    // cek parameter
        $this->_Check_prm_data($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $this->_Check_prm_data($in_codepln,'614',$trace_id, 'code isempty');       //
        $this->_Check_agent_data($in_agenid,'612',$trace_id);            //***
        //$this->_Check_kdpin_data($in_agenid, $in_kdepin,'615',$trace_id); // pin
        if (strlen($no_bpjsid) < 8)
	{            
            $data = array('status' => FALSE,'error_code' => '616','message' => 'BPJSID invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, plnid lenght ['.strlen($no_bpjsid).']');
            $this->response($data);  
            
        }
        if (!is_numeric($no_bpjsid))
	{
            $data = array('status' => FALSE,'error_code' => '616','message' => 'BPJSID invalid','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, plnid isnot numeric ['.$no_bpjsid.']');
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
        $this->logAction('info', $trace_id, array(), 'Transhoot->Bpjs_pay('.$in_agenid.','.$no_bpjsid.','.$trace_id.','.$in_rkning.','.$in_paytype.')');            
        $this->CTStart();
        $this->load->model('Transhoot');
        $trans = $this->Transhoot->Bpjs_pay($in_agenid,$no_bpjsid,$trace_id,$in_rkning,$in_paytype);
        if($trans){
            if(is_array($trans)){
                foreach ($trans as $v) {
                    $data_tagihan       = $v['tagihan'];
                    $topup_no_serial    = $v['sn'];
                    $gen_id_TABTRANS_ID = $v['tabtransid'];
                }
            }
        }else{
//            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data'=>array('sn' => '','code' => ''));
//            $this->logAction('response', $trace_id, $data, 'failed, response failed ['.$trans.']');
//            $this->response($data); 
            return;
        }
        
        $this->logAction('result', $trace_id, array(), 'response');
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $no_bpjsid." Rp".$this->Rp($topup_tagihan)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"PAY - Pembayaran BPJS BERHASIL",$gen_message);          
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }
        $arrdata = array(
            'sn' => $topup_no_serial,
            'code' => $trace_id,            
        );        
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
        $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
        //$this->logAction('info', $trace_id, $dataupd, 'upd');
        $this->Pay_model->Upd_logpayment($trace_id,$dataupd);          
        
        $this->logAction('result', $trace_id, array(), 'success, running trasaction');
        $this->logAction('update', $trace_id, array(), 'Update log pulsa');
        $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
        $this->logAction('response', $trace_id, $data, '');
        $this->response($data);                
    }    
    function _Kuitansi() {
        $result_kwitn = $this->Com_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
            }else{
                $out_nokwi = "";
              }
            if(empty($out_nokwi)){
                $out_nokwi = date('md')."001";
            }else{
                $out_nokwi = $out_nokwi + 1;
            }
            return $out_nokwi;
    }
    protected function _Kodetrans_by_desc($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Com_model->Kodetrans_by_kodetrans($in_kd_trans);
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
        $kodetrans_desc = $this->Com_model->Kodetrans_by_kodetrans($in_kd_trans);
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
    function _Check_agent_topup($in_agent_id = null,$error_code,$trace_id = '',$arr_custom = ''){
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{ 
            $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'sn' => '', 'code' => '');
            if(empty($arr_custom)){                
            }else{
                $data['data'] = $arr_custom;
            }
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_agent_data($in_agent_id = null,$error_code,$trace_id = '',$data = ''){
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'data' => $data);
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return; }
            
    }
    function _Check_prm($var = '',$errorcode = '',$trace_id = '',$field = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'sn' => '');
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);            
        }
    }
    function _Check_prm_data($var = '',$errorcode = '',$trace_id = '',$field = '',$data = '') {
        if(empty($var)){
            //$arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'data' => array('sn' => '','code' => ''));
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'data' => $this->res_result);
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);            
        }
    }
    function _Check_prm_pln_pay($var = '',$errorcode = '',$trace_id = '',$field = '',$data = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'data' => array('sn' => '','code' => ''));
            //$arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'data' => $this->res_result);
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);            
        }
    }
    function _Check_prm_bpjs($var = '',$errorcode = '',$trace_id = '',$field = '',$data = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'data' => $this->res_bpjs_inq);
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);            
        }
    }
    function _Check_prm_topup($var = '',$errorcode = '',$trace_id = '',$field = '', $arr_custom = '') {
        if(empty($var)){
            $arr_res   = array('status' => FALSE,'error_code' => $errorcode,'message' => $field,'sn' =>'','code' => '');
            if(empty($arr_custom)){                
            }else{
                $arr_res['data'] = $arr_custom;
            }
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);
            
        }
    }
    function _Tgl_hari_ini(){
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
    function Rp($value = null)    {
        return number_format($value,0,",",".");
    }
    protected function _tabung($in_rkning = '',$in_nominal = '',$in_agentid = '',$in_kode_perk_hutang_pokok_in_integrasi = '',$in_kode_perk_kas = '') {
        
        if(empty($in_rkning) || empty($in_agentid) || empty($in_nominal) || empty($in_kode_perk_hutang_pokok_in_integrasi) || empty($in_kode_perk_kas)){
            return false;
        }
        $gen_id_TABTRANS_ID         = $this->App_model->Gen_id();
        $gen_id_COMMON_ID           = $this->App_model->Gen_id();
        $gen_id_MASTER              = $this->App_model->Gen_id();
        $in_KUITANSI    = $this->_Kuitansi(); 
        $res_kode_jurnal = 'TAB';
        
        $in_keterangan = "Setoran transaksi sementara";
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'MY_KODE_TRANS'     =>PULSA_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$in_nominal,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agentid, 
            'KODE_TRANS'        =>PULSA_CODE,
            'TOB'               =>'T', 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );

            $res_ins    = $this->Tab_model->Ins_Tbl('TABTRANS',$arr_data);
            $arr_master = array(
                    'TRANS_ID'          =>  $gen_id_MASTER, 
                    'KODE_JURNAL'       =>  $res_kode_jurnal, 
                    'NO_BUKTI'          =>  $in_KUITANSI, 
                    'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                    'URAIAN'            =>  $in_keterangan, 
                    'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                    'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID, 
                    'USERID'            =>  $in_agentid, 
                    'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
                );
            $ar_trans_detail = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, 
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_nominal                                                             
                ),array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  $in_nominal, 
                    'KREDIT'        =>  0
                )
            );       
            $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            $this->Tab_model->Saldo_upd_by_rekening($in_rkning);
    }
    function is_json($str)    {
        return is_array(json_decode($str,true));
    }
    public function History_post() {
        //mutasi data transaksi commerce
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        
        $in_agentid = $this->input->post('agentid') ?: '';
        $in_dtm_fr = $this->input->post('date_fr') ?: '';
        $in_dtm_to = $this->input->post('date_to') ?: '';
        if(empty($in_agentid)){
            $arr_data= array(
                    'tgl'       => '',
                    'nomor'     => '',
                    'produk'    => '',
                    'harga'     => '',
                    'sn'        => '',                    
                    'status'    => ''
                );
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_fr)){
            $arr_data= array(
                    'tgl'       => '',
                    'nomor'     => '',
                    'produk'    => '',
                    'harga'     => '',
                    'sn'        => '',                    
                    'status'    => ''
                );
            $arr_res   = array('status' => FALSE,'error_code' => '602','message' => 'tanggal from isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_to)){
            $arr_data= array(
                    'tgl'       => '',
                    'nomor'     => '',
                    'produk'    => '',
                    'harga'     => '',
                    'sn'        => '',                    
                    'status'    => ''
                );
            $arr_res   = array('status' => FALSE,'error_code' => '603','message' => 'tanggal to isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        
        $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agentid);
        $this->logAction('info', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agentid.']');
        if($res_call_user_agent){
        }else{
            $arr_data = array(
                    'total_transaksi' => 0,
                    'profit' => array('cash' => 0, 'debit_tabungan' => 0),
                    'total_profit' => 0,
                    'total_setoran' => 0                   
               );
            $arr_res   = array('status' => FALSE,'error_code' => '605','message' => 'agentid tidak terdaftar','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        $res_countday = $this->Count_day($in_dtm_to, $in_dtm_fr);   
        $this->logAction('info', $trace_id, array(), 'periode hari : '.$res_countday); 
        if($res_countday == 0 || $res_countday > 7){            
            $arr_data= array(
                    'tgl'       => '',
                    'nomor'     => '',
                    'produk'    => '',
                    'harga'     => '',
                    'sn'        => '',                    
                    'status'    => ''
                );
            $arr_res   = array('status' => FALSE,'error_code' => '606','message' => 'batas maksimal 7 hari','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        
        $this->logAction('info', $trace_id, array(), 'periode hari : '.$res_countday); 
        $this->logAction('info', $trace_id, array(), 'get data : Com_model->Hist_per_periode('.$in_agentid.','.$in_dtm_fr.','.$in_dtm_to.')'); 
        $get_res = $this->Com_model->Hist_per_periode($in_agentid,$in_dtm_fr,$in_dtm_to);
        if($get_res){
            foreach ($get_res as $v) {
                if($v->res_code == 00){
                    $msg_status = 'sucess';
                }else{
                    $msg_status = 'pending';
                }
                $arr_data[]= array(
                    'tgl'       => $v->dtm,
                    'nomor'     => $v->msisdn,
                    'produk'    => $v->nominal.'-'.$v->product,
                    'harga'     => $this->Rp($v->price_selling),
                    'sn'        => $v->res_sn,                    
                    'status'    => $msg_status ?: '-'
                );
            }
            $arr_res   = array('status' => TRUE,'error_code' => '600','message' => 'sukses','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
            
        }else{
            $arr_data= array(
                    'tgl'       => '',
                    'nomor'     => '',
                    'produk'    => '',
                    'harga'     => '',
                    'sn'        => '',                    
                    'status'    => ''
                );
            $arr_res   = array('status' => FALSE,'error_code' => '604','message' => 'data history isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }           
    }
    public function Tagihan_post() {
        //mutasi data transaksi commerce
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        
        $in_agentid     = $this->input->post('agentid') ?: '';
        $in_dtm_fr      = $this->input->post('date_fr') ?: '';
        $in_dtm_to      = $this->input->post('date_to') ?: '';
        
        //
        if(empty($in_agentid)){
            $arr_data = array(
                    'total_transaksi' => 0,
                    'profit' => array('cash' => 0, 'debit_tabungan' => 0),
                    'total_profit' => 0,
                    'total_setoran' => 0                   
               );
            $arr_res   = array('status' => FALSE,'error_code' => '601','message' => 'agentid isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_fr)){
            $arr_data = array(
                    'total_transaksi' => 0,
                    'profit' => array('cash' => 0, 'debit_tabungan' => 0),
                    'total_profit' => 0,
                    'total_setoran' => 0                   
               );
            $arr_res   = array('status' => FALSE,'error_code' => '602','message' => 'tanggal from isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        if(empty($in_dtm_to)){
            $arr_data = array(
                    'total_transaksi' => 0,
                    'profit' => array('cash' => 0, 'debit_tabungan' => 0),
                    'total_profit' => 0,
                    'total_setoran' => 0                   
               );
            $arr_res   = array('status' => FALSE,'error_code' => '603','message' => 'tanggal to isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        
        $res_countday = $this->Count_day($in_dtm_to, $in_dtm_fr);   
        $this->logAction('info', $trace_id, array(), 'periode hari : '.$res_countday); 
        if(!$res_countday){
            return;
        }
        if($res_countday <= 0 || $res_countday > 7){            
            $arr_data= array(
                    'tgl'       => '',
                    'nomor'     => '',
                    'produk'    => '',
                    'harga'     => '',
                    'sn'        => '',                    
                    'status'    => ''
                );
            $arr_res   = array('status' => FALSE,'error_code' => '606','message' => 'batas maksimal 7 hari','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        
        $res_call_user_agent = $this->Admin_user2_model->User_by_username($in_agentid);
        $this->logAction('info', $trace_id, array(), 'Admin_user2_model->User_by_username ['.$in_agentid.']');
        if($res_call_user_agent){
        }else{
            $arr_data = array(
                    'total_transaksi' => 0,
                    'profit' => array('cash' => 0, 'debit_tabungan' => 0),
                    'total_profit' => 0,
                    'total_setoran' => 0                   
               );
            $arr_res   = array('status' => FALSE,'error_code' => '605','message' => 'agentid tidak terdaftar','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
        }
        //
        
        
        $getdata = $this->Com_model->Tgh_mutasi($in_agentid,$in_dtm_fr,$in_dtm_to);               
       if($getdata): 
                foreach ($getdata as $v) {                                   
                    if($v->KODE_TRANS == "101"){ //via cash
                        $this->mn_cash_awal   = $this->mn_cash_awal + $v->price_bmt;
                        $this->mn_cash_akhir  = $this->mn_cash_akhir + $v->total;
                        $this->mn_cash_adm    = $this->mn_cash_adm + $v->adm;
                        $this->mn_cash_adm    = $this->mn_cash_adm + $v->adm;
                    }elseif($v->KODE_TRANS == "102"){ //via debet tabungan
                        $this->mn_debt_awal   = $this->mn_debt_awal + $v->price_bmt;
                        $this->mn_debt_akhir  = $this->mn_debt_akhir + $v->total;
                        $this->mn_debt_adm    = $this->mn_debt_adm + $v->adm;
                        $this->mn_debt_adm    = $this->mn_debt_adm + $v->adm;
                    } 
                }             
                
                $total_transaksi    = $this->mn_cash_akhir + $this->mn_cash_adm + $this->mn_debt_akhir + $this->mn_debt_adm;
                $profit_cash        = $this->mn_cash_akhir - $this->mn_cash_awal;
                $profit_debt        = $this->mn_debt_akhir - $this->mn_debt_awal;
                
                $total_setoran      = $this->mn_cash_awal - $profit_debt;        
               
               $arr_data = array(
                    'total_transaksi' => $this->Rp($total_transaksi,''),
                    'profit' => array('cash' => $this->Rp($profit_cash,''), 'debit_tabungan' => $this->Rp($profit_debt,'')),
                    'total_profit' => $this->Rp($profit_cash + $profit_debt,''),
                    'total_setoran' => $this->Rp($total_setoran,'')                   
               );
                $arr_res   = array('status' => FALSE,'error_code' => '600','message' => 'ok','data' =>$arr_data);       
                $this->logAction('response', $trace_id, $arr_res, '');
                $this->response($arr_res);
          else:
              $arr_data = array(
                    'total_transaksi' => 0,
                    'profit' => array('cash' => 0, 'debit_tabungan' => 0),
                    'total_profit' => 0,
                    'total_setoran' => 0                   
               );
            $arr_res   = array('status' => FALSE,'error_code' => '604','message' => 'data history isempty','data' =>$arr_data);       
            $this->logAction('response', $trace_id, $arr_res, '');
            $this->response($arr_res);
            endif;
    }      
    public function Provider_by_prefix_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_msisdn  = $this->input->post('msisdn') ?: '';
        $in_tipe  = $this->input->post('tipe') ?: ''; //pulsa data
        if(empty($in_msisdn)){
            $data = array('status'  => FALSE,
                'errorcode'         => '611',
                'message'           => 'nomor is empty',
                'provider'          => '',
                'list'              => array(                    
                    'product_id'    => '',
                    'product_name'  => '',                    
                    'price'         => ''
                ));
            $this->response($data);
        }
        
        if(strtolower($in_tipe) == "data" || strtolower($in_tipe) == "pulsa"):
            $in_tipe    = strtolower($in_tipe);
        else:    
            $data = array('status'  => FALSE,
                'errorcode'         => '611',
                'message'           => 'tipe is empty',
                'provider'          => '',
                'list'              => array(                    
                    'product_id'    => '',
                    'product_name'  => '',                    
                    'price'         => ''
                ));
            $this->response($data);
        endif;
        
        
        $in_msisdn      = $this->Msisdn_filter($in_msisdn);
        $in_msisdn      = substr($in_msisdn, 0, 4);
        
        $in_code_operator = $this->Com_model->Prefix_get_provider($in_msisdn,$in_tipe) ?: 0;
        
        if($in_code_operator === 0){
            $data = array('status'  => FALSE,
                'errorcode'         => '612',
                'message'           => 'Product tidak tersedia',
                'provider'          => '',
                'list'              => array(                    
                    'product_id'    => '',
                    'product_name'  => '',                    
                    'price'         => ''
                ));
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
                    //'provider'    => $val_list->provider,
                    'product_id'    => $val_list->product_id,
                    'product_name'  => $alias,                    
                    'price'         => $this->Rp($val_list->price_selling)
                );
            }
            $data = array('status'  => TRUE,
                'errorcode'         => '600' , 
                'message'           => 'success' , 
                'provider'          => $val_list->provider,
                'list'              => $arr);
            $this->logAction('response', $trace_id, $data, 'success');
            $this->response($data);
        }
        else{
            $data = array('status'  => FALSE,
                'errorcode'         => '613' , 
                'message'           => 'Product Kosong' ,
                'provider'          => '',
                'list'              => array(                    
                    'product_id'    => '',
                    'product_name'  => '',                    
                    'price'         => ''
                ));
            $this->logAction('response', $trace_id, $data, 'failed,data is empty');
            $this->response($data);
        }     
    }
    public function Provider_list_general_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $in_provider = 'PULSA';
        if(empty($in_provider)){
            $data = array('status' => FALSE,
                'errorcode' => '611',
                'message' => 'category is empty',
                'list' => '');
            $this->logAction('response', $trace_id, $data, '');;
            $this->response($data);
        }
        $res_list = $this->Pay_model->List_op_product_lainya(strtolower($in_provider));
        $this->logAction('select', $trace_id,array(), 'Pay_model->List_op_product_lainya("'.$in_provider.'")');
        $arr = array();
        if($res_list){
            foreach ($res_list as $val_list) {
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
    public function LogCommerce_post() {
        $arr_res = array();
        //$getdata = $this->Com_model->Tgh_mutasi_pay('258','2018-01-19','2018-01-19');
        $in_agentid = $this->input->post('agentid') ?: '';
        if(empty($in_agentid) || strlen($in_agentid) > 6 || !is_numeric($in_agentid)){
            
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid agentid',
                'data'      => array($this->arr_logcommerce)
            );
           $this->response($arrfin);           
        }
        $getdata = $this->Com_model->Commerce_per_day($in_agentid);
        if($getdata){            
            foreach ($getdata as $v) {
                $o_dtm          = $v->dtm ?: '';
                $o_nomorid      = $v->nomorid ?: '';
                $o_product      = $v->product ?: '';
                $o_kategori     = $v->kategori ?: '';
                $o_price        = $v->price ?: '';
                $o_sn           = $v->sn ?: '';
                $o_code         = $v->code ?: '';
            
                if($o_kategori == "PLN"){
                    //"kode_token":"3199-2792-4713-1396-0083/CAHYONO/R1M/900/13,6",
                    list($get_token, $get_nama, $get_tarif, $get_daya, $get_jml_kwh) = explode("/", $o_sn);
                    $get_tarif_daya     =  $get_tarif.'/'.$get_daya;
                }else{
                    $get_token      = $o_sn;
                    $get_nama       = '';
                    $get_tarif_daya = '';
                    $get_jml_kwh    = '';
                }
                $arr_res[] = array(
                    'dtm'           => $o_dtm,
                    'nomorid'       => $o_nomorid,
                    'product'       => $o_product,
                    'kategori'      => $o_kategori,
                    'price'         => $this->Rp($o_price),
                    'sn'            => $get_token,
                    'nama'          => $get_nama,
                    'tarif_daya'    => $get_tarif_daya,
                    'jml_kwh'       => $get_jml_kwh,
                );
            }
            $arrfin = array(
                'errorcode' => '100',
                'msg'       => 'ok',
                'data'      => $arr_res
            );
        }else{
            $arrfin = array(
                'errorcode' => '102',
                'msg'       => 'not found',
                'data'      => array($this->arr_logcommerce)
            );
        }
        
        $this->response($arrfin);
    } 
    public function LogPayment_PLN_post() {
        $arr_res = array();
        //$getdata = $this->Com_model->Tgh_mutasi_pay('258','2018-01-19','2018-01-19');
        $in_agentid = $this->input->post('agentid') ?: '';
        if(empty($in_agentid) || strlen($in_agentid) > 6 || !is_numeric($in_agentid)){
            
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid agentid',
                'data'      => $this->arr_logpayment_pln
            );
           $this->response($arrfin);           
        }
        $getdata = $this->Com_model->Payment_pln_per_day($in_agentid);
        if($getdata){            
            foreach ($getdata as $v) {
                $o_dtm          = $v->dtm ?: '';
                $o_cust_id      = $v->cust_id ?: '';
                $o_cust_name    = $v->cust_name ?: '';
                $o_periode1     = $v->periode1 ?: '';
                $o_periode2     = $v->periode2 ?: '';
                $o_periode3     = $v->periode3 ?: '';
                $o_periode4     = $v->periode4 ?: '';
                $o_price        = $v->POKOK ?: '';
                $o_adm          = $v->ADM ?: '';
                $o_total        = $v->total ?: '';
                $o_sn           = $v->res_sn ?: '';
            
                
                $arr_res[] = array(
                    'dtm'           => $o_dtm,
                    'nomorid'       => $o_cust_id,
                    'custname'      => $o_cust_name,
                    'kategori'      => 'PLN',
                    'periode1'      => $o_periode1,
                    'periode2'      => $o_periode2,
                    'periode3'      => $o_periode3,
                    'periode4'      => $o_periode4,
                    'tagihan'       => $this->Rp($o_price),
                    'adm'           => $this->Rp($o_adm),
                    'total_bayar'   => $this->Rp($o_total),
                    'sn'            => $o_sn
                );
            }
            $arrfin = array(
                'errorcode' => '100',
                'msg'       => 'ok',
                'data'      => $arr_res
            );
        }else{            
            $arrfin = array(
                'errorcode' => '102',
                'msg'       => 'not found',
                'data'      => $this->arr_logpayment_pln
            );
        }        
        $this->response($arrfin);
    }
    public function LogPayment_BPJS_post() {
        $arr_res = array();
        //$getdata = $this->Com_model->Tgh_mutasi_pay('258','2018-01-19','2018-01-19');
        $in_agentid = $this->input->post('agentid') ?: '';
        if(empty($in_agentid) || strlen($in_agentid) > 6 || !is_numeric($in_agentid)){
            
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid agentid',
                'data'      => $this->arr_logpayment_bpjs
            );
           $this->response($arrfin);           
        }
        $getdata = $this->Com_model->Payment_bpjs_per_day($in_agentid);
        if($getdata){            
            foreach ($getdata as $v) {
                $o_dtm          = $v->dtm ?: '';
                $o_cust_id      = $v->cust_id ?: '';
                $o_cust_name    = $v->cust_name ?: '';
                $o_periode1     = $v->periode ?: '';
                $o_price        = $v->POKOK ?: '';
                $o_adm          = $v->ADM ?: '';
                $o_total        = $v->total_amount ?: '';
                $o_sn           = $v->res_sn ?: '';
                $o_totalperson  = $v->total_person ?: '';
                $o_no_reff      = $v->no_reff ?: '';
            
                
                $arr_res[] = array(
                    'dtm'           => $o_dtm,
                    'nomorid'       => $o_cust_id,
                    'custname'      => $o_cust_name,
                    'kategori'      => 'BPJS',
                    'periode'      => $o_periode1,
                    'totalperson'   => $o_totalperson,
                    'no_reff'       => $o_no_reff,
                    'tagihan'       => $this->Rp($o_price),
                    'adm'           => $this->Rp($o_adm),
                    'total_bayar'   => $this->Rp($o_total),
                    'sn'            => $o_sn
                );
            }
            $arrfin = array(
                'errorcode' => '100',
                'msg'       => 'ok',
                'data'      => $arr_res
            );
        }else{            
            $arrfin = array(
                'errorcode' => '102',
                'msg'       => 'not found',
                'data'      => $this->arr_logpayment_bpjs
            );
        }        
        $this->response($arrfin);
    }
    
}

define('EXPTIMEINFO', '1');
define('PULSA_CODE', '227');
define('PULSA_MYCODE', '200');
define('PULSA_INDOSIS_CODE', '127');
define('PULSA_INDOSIS_MYCODE', '100');
define('PLN_INDOSIS_CODE', '129');
define('PLN_INDOSIS_MYCODE', '100');
define('PLN_POSTPAID_INDOSIS_CODE', '130');
define('PLN_POSTPAID_INDOSIS_MYCODE', '100');
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
define('PROVIDER_CODE_BPJS', '123');
define('PROVIDER_CODE_FINANCE', '113');
define('BPJS_CODE', '231');
define('BPJS_MYCODE', '200');
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
//define('URL_BPJS_INQ', 'http://202.43.173.13/API_BMT_NEW/21_request_inquiry_bpjs.php');
//define('URL_BPJS_PAY', 'http://202.43.173.13/API_BMT_NEW/22_request_payment_bpjs.php');
define('URL_BPJS_INQ', 'http://10.1.1.62/API_BMT_NEW/21_request_inquiry_bpjs.php');
define('URL_BPJS_PAY', 'http://10.1.1.62/API_BMT_NEW/22_request_payment_bpjs.php');
