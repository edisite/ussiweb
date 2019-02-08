<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transhoot
 *
 * @author edisite
 */
class Transhoot extends MY_Model{
    //put your code here
    private $res_result = array(
            'meterid'       => '',
            'cust_name'     => '',
            'daya'          => '',
            'periode'       => '',
            'tagihan'       => '',
            'adm'           => '',            
            'total'         => '',            
            'code'          => ''            
        );
    
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
    private $res_product = false;
    public function __construct() {
        $trans_id = random_string('numeric', '20');
    }
    public function Bpjs_inq($in_agenid = '',$no_bpjsid = '',$in_period = '',$trace_id = '') {
        $trans_id = random_string('numeric', '20');
        
        $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_BPJS);
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid                  = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                $this->biaya_adm            = $val_postpaid->adm_payment_total  ? $val_postpaid->adm_payment_total : 0;
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $this->res_bpjs_inq);
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->List_product('.PROVIDER_CODE_POSPAID.')');
            $this->response($data);
            return;
        }
        
        $URL = URL_BPJS_INQ;
        $URL = $URL."?product_code=".$productid;
        $URL = $URL."&trx_id=".$trans_id;
        $URL = $URL."&cust_id=".$no_bpjsid;
        $URL = $URL."&periode=".$in_period;        
        $res_call_hit_topup = $this->Hitget($URL);
        //$res_call_hit_topup = '{"status":"OK","rc":"00","product_code":"9001","trx_id":"68415007529323741869","cust_id":"0001477850905","cust_name":"SUPARMAN","total_amount":"82500","amount":"80000","admin_fee":"2500","periode":"02","total_person":"1","no_reff":"94990674A2FB26CA","message":"Berhasil","provider":"Mitracomm"}';
        $this->logAction('info', $trace_id, array(), 'hit HTTP : '.$URL);
        $this->logAction('result', $trace_id, array(), 'get response : '.$res_call_hit_topup);
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
            $topup_total_amount     = isset($data_topup->total_amount) ? $data_topup->total_amount : NULL;
            $topup_amount           = isset($data_topup->amount) ? $data_topup->amount : NULL;
            $topup_admin_fee        = isset($data_topup->admin_fee) ? $data_topup->admin_fee : NULL;
            $topup_periode          = isset($data_topup->periode) ? $data_topup->periode : NULL;
            $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
            $topup_total_person     = isset($data_topup->total_person) ? $data_topup->total_person : '';
            $topup_no_reff          = isset($data_topup->no_reff) ? $data_topup->no_reff : '';
            $this->logAction('response', $trace_id, array(), 'OK');
        }else{
             $this->logAction('response', $trace_id, array(), 'error response ');
             $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_bpjs_inq);
             $this->response($data);
             return;
        }
        if(strtolower($topup_status) == 'ok'){
        }else{
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_bpjs_inq);
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            return;
        }
        $this->logAction('response', $trace_id, array(), 'OK - ['.$topup_respon_code.']');
        if($topup_respon_code != '00'){            
            if($topup_respon_code == '14'                                                                                                                                                                                                                            ){
                $data = array('status' => FALSE,'error_code' => '612','message' => 'Nomor Bpjs invalid','data' => $this->res_bpjs_inq);          
            }elseif($topup_respon_code == '40' || $topup_respon_code == '89' || $topup_respon_code == '91' || $topup_respon_code == '05'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_bpjs_inq);
            }elseif($topup_respon_code == '92'){
                $data = array('status' => FALSE,'error_code' => '601','message' => 'Inquiry Gagal','data' => $this->res_bpjs_inq);
            }elseif($topup_respon_code == '13'){
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_bpjs_inq);
            }elseif($topup_respon_code == '51'){
                $data = array('status' => FALSE,'error_code' => '602','message' => 'saldo insufficent balance','data' => $this->res_bpjs_inq);
            }elseif($topup_respon_code == '68'){
                $data = array('status' => FALSE,'error_code' => '603','message' => 'Pending','data' => $this->res_bpjs_inq);
            }elseif($topup_respon_code == '88'){
                $data = array('status' => FALSE,'error_code' => '602','message' => 'Lunas','data' => $this->res_bpjs_inq);
            }else{
                $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $this->res_bpjs_inq);
            }  
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->Response($data);
            return;
        }
        if(!$this->Tab_model->Ins_Tbl('com_bpjs_ses',$data_topup)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $this->res_bpjs_inq);
            $this->logAction('insert', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("com_pulsa_log")');
            $this->response($data);
            return;
        }
        
        $this->tot_tagihan  = $topup_amount;
        $this->total        = $this->tot_tagihan + $this->biaya_adm;
        $arr_upd = array(
            'log_trace'             => $trace_id,
            'total_adm'             => $this->biaya_adm,
            'total_tagihan'         => $this->total
        );
        $this->LogAction('update', $trace_id, $arr_upd, 'Pay_model->Upd_logbpjs');
        $this->Pay_model->Upd_logbpjs($topup_transid,$arr_upd);
        $res_result = array(
            'bpjsid'        => $topup_cust_id,
            'cust_name'     => $topup_cust_name,
            'periode'       => $topup_periode,
            'total_person'  => $topup_total_person,
            'no_reff'       => $topup_no_reff,
            'tagihan'       => $this->Rp($this->tot_tagihan),
            'adm'           => $this->Rp($this->biaya_adm),            
            'total'         => $this->Rp($this->total),            
            'code'          => $trace_id            
        );
        $this->LogAction('info', $trace_id, $res_result, 'tagihan');
        return $res_result; 
    }
    protected function Hitget($url = '')    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    protected function LogAction($strAction,$trace_id = '', array $arrData = NULL, $message = NULL) {
            if(empty($trace_id)){
                $trace_id = random_string('alnum','14');
            }
            $strMessage = '';
            $strMessage .= 'Action: ' . $strAction . ' |';
            $strMessage .= 'API|';
            $strMessage .= $trace_id;
            //$strMessage .= '[' . $this->input->ip_address() . '] '; 
            // add data if provided
            if($message){
                $strMessage .= ' |message: ['.$message.']';
            }
            if ($arrData) {
                $strMessage .= ' ---|data: ' . str_replace(array("\n", "\r", "    "), '', print_r($arrData, true));
            }

            log_message('info', $strMessage);
        }
    private function Response($arr = array()) {        
        $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                //->_display();
//        header('Content-Type: application/json');        
//        echo json_encode($arr);
//        return;
    }
    function Rp($value = null)    {
        return number_format($value,0,",",".");
    }
    
    public function Bpjs_pay($in_agenid,$no_bpjsid,$trace_id,$in_rkning,$in_paytype) {
        $res_product = $this->Pay_model->Bpjs_ses_by_code($trace_id,$no_bpjsid);
        if($res_product){
            foreach ($res_product as $sub_product) {
                $topup_status           = isset($sub_product->status) ? $sub_product->status : '' ;
                $topup_respon_code      = isset($sub_product->rc) ? $sub_product->rc : '';
                $topup_message          = isset($sub_product->message) ? $sub_product->message : '';
                $topup_product_code     = isset($sub_product->product_code) ? $sub_product->product_code : NULL;
                $topup_transid          = isset($sub_product->trx_id) ? $sub_product->trx_id : NULL;
                $topup_cust_id          = isset($sub_product->cust_id) ? $sub_product->cust_id : NULL;
                $topup_cust_name        = isset($sub_product->cust_name) ? $sub_product->cust_name : NULL;
                $topup_admin_fee        = isset($sub_product->admin_fee) ? $sub_product->admin_fee : 0;
                $topup_periode          = isset($sub_product->periode) ? $sub_product->periode : NULL;
                $topup_amount           = isset($sub_product->amount) ? $sub_product->amount : 0;
                $topup_total_amount     = isset($sub_product->total_amount) ? $sub_product->total_amount : 0;
                $topup_tagihan          = isset($sub_product->total_tagihan) ? $sub_product->total_tagihan : 0;
                $topup_biaya_adm        = isset($sub_product->total_adm) ? $sub_product->total_adm : 0;
                $topup_total_person        = isset($sub_product->total_person) ? $sub_product->total_person : 0;
                $this->topup_statushit  = isset($sub_product->status_hit) ? $sub_product->status_hit : 0;
            }
        }else{
            $data = array('status' => FALSE,'error_code' => '632','message' => 'Error Internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Bpjs_ses_by_code('.$trace_id.','.$no_bpjsid.')');
            $this->response($data); 
            return;
        }  
        if($this->topup_statushit > 1){
            $this->logAction('info', $trace_id, array(), 'topup_statushit ('.$this->topup_statushit.')');
            $data = array('status' => FALSE,'error_code' => '622','message' => 'Session Expired','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Code sudah pernah digunakan, Session Expired -- ('.$trace_id.','.$no_bpjsid.')');
            $this->response($data);
            return;
        }    
            
        $this->Pay_model->Upd_bpjs_ses_by_code($trace_id,$no_bpjsid,array('status_hit' => $this->topup_statushit + 1));
        $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_BPJS);
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid          = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;
                $this->biaya_adm_indo     = $val_postpaid->price_original  ? $val_postpaid->price_original : 0;
                $this->biaya_adm_bmt      = $val_postpaid->price_stok  ? $val_postpaid->price_stok : 0;
                $this->biaya_adm_agen     = $val_postpaid->price_selling  ? $val_postpaid->price_selling : 0;
                $this->biaya_adm_prov     = $val_postpaid->adm_payment_provider  ? $val_postpaid->adm_payment_provider : 0;
            }
            $this->biaya_adm = $this->biaya_adm_indo + $this->biaya_adm_bmt + $this->biaya_adm_agen;
        }else{
           $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
           $this->logAction('response', $trace_id, $data, 'failed');
           $this->response($data);
           return;
        }
        
        //**** cek nasabah
        if(strtolower($in_paytype) == "nasabah"){
            $res_cek_rek = $this->Tab_model->Tab_nas($in_rkning);
            if(!$res_cek_rek){            
                $data = array('status' => FALSE,'error_code' => '619','message' => 'rekening invalid','data' => array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_rkning.')');
                $this->response($data);
                return;
            }
            foreach ($res_cek_rek as $sub_cek_rek) {
                $nama_nasabah   = $sub_cek_rek->nama_nasabah;
                $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
                $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
            }
            $this->logAction('info', $trace_id, array(), 'total biaya : '.$topup_tagihan);
            $this->logAction('info', $trace_id, array(), 'saldo rekening : '.$cek_saldo_akhir);
            if(floatval(round($topup_tagihan)) > round($cek_saldo_akhir)){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'saldo tabungan tidak cukup','data' => array('sn' => '','code' => ''));
                $this->logAction('response', $trace_id, $data, 'failed, saldo insufficent balace');
                $this->response($data);
                return;

            }
            //get rekening indosis
            $get_nomor_rekening_partner = $this->Tab_model->Acc_by_partnerid('INDOSIS_PRODUCTION');
            $this->logAction('transaction', $trace_id, array(), 'get rekening partner :Tab_model->Acc_by_partnerid(INDOSIS_PRODUCTION)');
            if(empty($get_nomor_rekening_partner)){
                $data = array('status' => TRUE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
                $this->logAction('response', $trace_id, $data, 'failed');
                $this->response($data);
                return;                
            }
            $this->logAction('transaction', $trace_id, array(), 'rek partner : '.$get_nomor_rekening_partner);
            
        }else{
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('insert', $trace_id, $data, 'paytype not found');
            $this->response($data);
        }
         
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URL_BPJS_PAY;
        $URL = $URL."?product_code=".$topup_product_code;
        $URL = $URL."&trx_id=".$topup_transid;
        $URL = $URL."&cust_id=".$topup_cust_id;
        $URL = $URL."&cust_name=".rawurlencode($topup_cust_name);
        $URL = $URL."&admin_fee=".round($topup_admin_fee);
        $URL = $URL."&periode=".$topup_periode;
        $URL = $URL."&total_amount=".round($topup_total_amount);
        $URL = $URL."&amount=".round($topup_amount);
        $URL = $URL."&person=".$topup_total_person;

        //partner_id=&product_code=&trx_id=&cust_id=&cust_name=&rec_payable=&rec_rest=&reffno_pln=&unit_svc=
        //&svc_contact=&tariff=&daya=&admin_fee=&periode1=&periode2=&periode3=&periode4=&duedate=&mtr_date=
        //&bill_amount=&insentif=&ppn=&penalty=&lwbp_last=&lwbp_crr=&wbp_last=&wbp_crr=&kvarh_last=&kvarh_crr=

        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $this->Hitget($URL);
        
        //$res_call_hit_topup = '{"status":"OK","rc":"00","product_code":"9001","trx_id":"68415007529323741869","cust_id":"1477850905","cust_name":"SUPARMAN","total_amount":"82500","amount":"80000","admin_fee":"2500","periode":"02","total_person":"1","receipt_code":"94990674A2FB26CA","message":"Berhasil","provider":"Mitracomm"}';
            if($res_call_hit_topup){
            $this->logAction('result', $trace_id, array(), 'response : '.$res_call_hit_topup);
            $data_topup = json_decode($res_call_hit_topup);
            //$this->logAction('response', $trace_id, $data_topup, '');
        
            $topup_status           = $data_topup->status ?: '';
            $topup_respon_code      = $data_topup->rc ?: '';
            $topup_message          = $data_topup->message ?: '';
            $topup_saldo            = /*$data_topup->saldo ?:*/ NULL;
            $topup_no_serial        = $data_topup->receipt_code ?: NULL;
            $topup_transid          = $data_topup->trx_id ?: NULL;
            $topup_provider         = $data_topup->provider ?: NULL;
            
            $this->logAction('parsing', $trace_id, array(), $topup_status);
            $this->logAction('parsing', $trace_id, array(), $topup_respon_code);
            $this->logAction('parsing', $trace_id, array(), $topup_message);
        }else{
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            return;
        }
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            return;
        }
        
        $com_pulsa_log = array(
            'request'       => $this->input->raw_input_stream,
            'ip_request'    => $this->input->ip_address(),
            'destid'        => $no_bpjsid,
            'product'       => $topup_product_code,
            'nominal'       => 'BPJS',
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
            'tagihan'       => $topup_amount,
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
        if($topup_respon_code == '00'){
        }else{
            if($topup_respon_code == '03'){
                $data = array('status' => FALSE,'error_code' => '613','message' => 'pin invalid','data' => array('sn' => '','code' => ''));                
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
            }elseif($topup_respon_code == '51'){
                $data = array('status' => FALSE,'error_code' => '623','message' => 'Saldo tidak cukup','data' => array('sn' => '','code' => ''));
            }else{
                $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data' => array('sn' => '','code' => ''));
            } 
            
            $this->logAction('geterr', $trace_id, array(), 'failed, provider responcode ['.$topup_respon_code.'] >> '.$topup_message);
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
            return;
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
        $in_keterangan                  = $in_desc_trans." ".$no_bpjsid." ".  $this->Rp($topup_tagihan)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $this->_Kuitansi();
              
        
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_rkning, 
            'COMTYPE'           =>'BPJS',
            'NOCUSTOMER'        =>$no_bpjsid,
            'MY_KODE_TRANS'     =>$res_mycode_kode, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$topup_amount,
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
            'MODEL'             =>'PAY',
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );

            
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
        if(!$res_ins){
           $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
           $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
           $this->response($data);
           return;
        }    
        if(strtolower($in_paytype) == 'nasabah'){
            //debit nasabah
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.''
                    . ','.$no_bpjsid.','.$in_agenid.','.$topup_tagihan.','.BPJS_CODE.','.BPJS_MYCODE.','.$gen_id_TABTRANS_ID.',"",TARIK');
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$no_bpjsid,$in_agenid,$topup_tagihan,BPJS_CODE,BPJS_MYCODE,$gen_id_TABTRANS_ID,'','TARIK');
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.''
                    . ','.$no_bpjsid.','.$in_agenid.','.$topup_tagihan.','.PULSA_INDOSIS_CODE.','
                    . ''.PULSA_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID.',"",SETOR');
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$no_bpjsid,$in_agenid,$topup_tagihan,BPJS_CODE,BPJS_CODE,$gen_id_TABTRANS_ID,'','SETOR');
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }            
        }
        $datacallback[] =  array(
            'tagihan'       => $topup_tagihan,
            'sn'            => $topup_no_serial,
            'tabtransid'    => $gen_id_TABTRANS_ID
        );
        return $datacallback;
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
    function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    
}

//define('URLPLN_BPJS_INQ', 'http://10.1.1.62/API_BMT_NEW/21_request_inquiry_bpjs.php');

//define('PROVIDER_CODE_BPJS', '123');