<?php
//include '../../Commerce.php';
require_once __DIR__ . '/../Commerce.php'; 
require_once __DIR__ . '/../Fcm.php'; 
/**
 * Description of Com
 *
 * @author edisite
 */
class Com extends API_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        
    }
    public function Topup_pulsa_post() {
        $c_com = new Commerce(); 
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('m_userid') ? $this->input->post('m_userid') : ''; //**
        $in_msisdn  = $this->input->post('msisdn') ? $this->input->post('msisdn') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : 'nasabah'; // value of agent or nasabah[90]
        $in_produk  = $this->input->post('produk') ? $this->input->post('produk') : '';        
        $in_kdepin  = $this->input->post('pin') ? $this->input->post('pin') : '9999';       //**
        
        $c_com->_Check_prm_topup($in_msisdn,'611',$trace_id, 'msisdn isempty');   //***
        $c_com->_Check_prm_topup($in_agenid,'612',$trace_id, 'm_userid isempty');   //***
        $c_com->_Check_prm_topup($in_msisdn,'614',$trace_id, 'msisdn isempty');    // cek parameter
        $c_com->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty');    // -
        //$c_com->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin isempty');       //
        $this->_Check_agent($in_agenid,'621',$trace_id);            //***
        //$this->_Check_kdpin($in_agenid, $in_kdepin,'622',$trace_id); // pin
        if (strlen($in_msisdn) < 8 || strlen($in_msisdn) > 16)	{            
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
            $c_com->_Check_prm($in_rkning,'613',$trace_id, 'rekening isempty');   //***            
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
            $r_price_original       = $sub_product->price_user_indosis;
            $r_price_stok           = $sub_product->price_user_selling;
            $r_price_selling        = $sub_product->price_user_selling;
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
        $URL = $URL."&jumlah=".$r_product_alias;
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        
        $this->CTStart();
        
        $res_call_hit_topup = $c_com->Hitget($URL);
        //$res_call_hit_topup = '{"status":"OK","rc":"00","kode_produk":"SN5","trx_id":"628551000727","saldo":"","no_hp":"081319292741","jumlah":"","message":"16\/05\/17 17:22 ISI SN5 KE 081319292741, Transaksi anda SDH DITERIMA dan sedang DIPROSES, PENDING OPERATOR mhn di tunggu SISA. SAL=356.990,HRG=5.950,ID=60006804, INFO : Perubahan Harga TSEL 5 utk RS silahkan cek harga , thx.","sn":"","provider":"Powermedia"}';
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' => '','code' => '');
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        //sleep(100);
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
            'price_fr_mitra'=> $topup_harga,
            'price_original'=> $r_price_original,
            'price_stok'    => $r_price_stok,
            'price_selling' => $r_price_selling,
            'res_sn'        => $topup_no_serial,
            'res_transid'   => $topup_transid,
            'log_trace'      => $trace_id,
            'provider'      => $topup_provider
        );
        
        
        $this->logAction('insert', $trace_id, $com_pulsa_log, 'Tab_model->Ins_Tbl("com_pulsa_log")');
        if(!$this->Tab_model->Ins_Tbl('com_pulsa_log',$com_pulsa_log)){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','sn' => '','code' => '');
            $this->logAction('response', $trace_id, $data, '');
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
            $in_desc_trans                  = $c_com->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $c_com->_Kodetrans_by_tob('100');
        }
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_msisdn." ".  $c_com->Rp($r_price_selling)." SN:.".$topup_no_serial;
        
        $in_KUITANSI    = $c_com->_Kuitansi();
              
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$c_com->_Tgl_hari_ini(),
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
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_TABTRANS_ID);
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_msisdn,$in_agenid,$r_price_selling,PULSA_CODE,PULSA_MYCODE,$gen_id_TABTRANS_ID);
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_msisdn.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_TABTRANS_ID);
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_msisdn,$in_agenid,$r_price_selling,PULSA_INDOSIS_CODE,PULSA_INDOSIS_MYCODE,$gen_id_TABTRANS_ID);
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }            
        }                                                                                                                                                                                                                                                      
        
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_msisdn." Rp".$c_com->Rp($r_price_selling)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            //include 'Fcm.php';
            
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PULSA BERHASIL",$gen_message);
           
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }       

        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code'=> $trace_id);
        $dataupd = array('master_id' => $gen_id_TABTRANS_ID);
        $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
        $this->logAction('result', $trace_id, array(), 'success, running trasaction');
        $this->logAction('update', $trace_id, array(), 'Update log pulsa');            
        $this->logAction('response', $trace_id, $data, '');
        $this->response($data);                   
    }     
    public function Topup_pln_token_post() {
        $c_com = new Commerce(); 
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid      = $this->input->post('m_userid') ?: ''; //**
        $in_pln         = $this->input->post('plnid') ?: '';  // post data
        $in_paytype     = $this->input->post('paytype') ?: 'nasabah'; // value of agent or nasabah[90]
        $in_produk      = $this->input->post('produk') ?: '';        
        $in_kdepin      = $this->input->post('pin') ?: '';       //**
        
        
        $c_com->_Check_prm_topup($in_agenid,'612',$trace_id, 'm_userid isempty');   //***
        $c_com->_Check_prm_topup($in_pln,'614',$trace_id, 'plnid isempty');    // cek parameter
        $c_com->_Check_prm_topup($in_produk,'615',$trace_id, 'produk isempty');    // -
        //$c_com->_Check_prm_topup($in_kdepin,'616',$trace_id, 'pin invalid');       //
        $this->_Check_agent($in_agenid,'621',$trace_id);            //***
        
        
        //$this->_Check_kdpin($in_agenid, $in_kdepin,'616',$trace_id); // pin
        
        if (strlen($in_pln) < 8 || strlen($in_pln) > 20)
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
            $c_com->_Check_prm($in_rkning,'613',$trace_id, 'rekening isempty');   //***            
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
            if(floatval(round($r_price_selling)) >= round($cek_saldo_akhir)){
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
        $this->CTStart();
        //*****************************************
        //lokasi hit ke server pln
        //*****************************************
        $URL = URLPLN;
        $URL = $URL."?id_pln=".$in_pln;
        $URL = $URL."&kode_produk=".rawurlencode($in_produk);
        //$URL = $URL."&jumlah=".$r_product_alias;
        $this->logAction('openhttp', $trace_id, array('url'=> $URL), 'hit url');
        $res_call_hit_topup = $c_com->Hitget($URL);
        //$res_call_hit_topup = '{"status":"OK","respon_code":"00","request_id":"628551000727","message":"SN=3561-2601-7730-8936-8010\/JOHNNYWENNYWEOL\/R1\/1300VA\/13,40;26\/01\/17 14:27 ISI PLN20 KE 01108490556, BERHASIL SISA. SAL=112.800,HRG=19.860,ID=57693947,SN=3561-2601-7730-8936-8010\/JOHNNYWENNYWEOL\/R1\/1300VA\/13,40; INFO : XL, AXIS, ISAT DATA untuk RS silahkan cek harga , thx.","kode_token":"3561-2601-7730-8936-8010\/JOHNNYWENNYWEOL\/R1\/1300VA\/13","saldo":"112800","id":"57693947","provider":"Powermedia"}';
        //sleep(12);
        
        if(!$res_call_hit_topup){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
            $this->response($data);
            //return;
        }
        if(!$c_com->is_json($res_call_hit_topup)){
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
        $topup_no_serial        = isset($data_topup->kode_token) ? $data_topup->kode_token : '';
        $topup_transid          = isset($data_topup->id) ? $data_topup->id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        
        if(strtolower($topup_status) == 'nok'){
            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','sn' =>'','code'=>'');
            $this->logAction('response', $trace_id, $data, 'failed, status topup NOK');
            $this->response($data);
            //return;
        }
        list($get_token, $get_nama, $get_tarif, $get_daya, $get_jml_kwh) = explode("/", $topup_no_serial);
        
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
            $in_desc_trans                  = $c_com->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $c_com->_Kodetrans_by_tob('100');
        }
        
        //revisi end
        $gen_id_COMTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();        
        $in_keterangan                  = $in_desc_trans." ".strtoupper($r_provider)." ".$in_pln." ".  $c_com->Rp($r_price_selling)." SN:92372892749748";       
        $in_KUITANSI    = $c_com->_Kuitansi();                    
        //revisi
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_COMTRANS_ID, 
            'TGL_TRANS'         =>$c_com->_Tgl_hari_ini(), 
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
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$in_rkning.','.$in_pln.','.$in_agenid.','.$r_price_selling.','.PULSA_CODE.','.PULSA_MYCODE.','.$gen_id_COMTRANS_ID);
            $res_debit_nasabah = $this->Tab_model->Tab_commerce($in_rkning,$in_pln,$in_agenid,$r_price_selling,PLN_TOKEN_CODE,PLN_TOKEN_MYCODE,$gen_id_COMTRANS_ID);
            
            //insert tab indosis
            if($res_debit_nasabah){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            $this->logAction('insert', $trace_id, array(), 'partner : Tab_model->Tab_commerce('.$get_nomor_rekening_partner.','.$in_pln.','.$in_agenid.','.$r_price_selling.','.PULSA_INDOSIS_CODE.','.PULSA_INDOSIS_MYCODE.','.$gen_id_COMTRANS_ID);
            $res_indosis = $this->Tab_model->Tab_commerce($get_nomor_rekening_partner,$in_pln,$in_agenid,$r_price_selling,PLN_INDOSIS_CODE,PLN_INDOSIS_MYCODE,$gen_id_COMTRANS_ID);
            if($res_indosis){
                $this->logAction('insert', $trace_id, array(), 'result : OK');
            }else{                
                $this->logAction('insert', $trace_id, array(), 'result : failed');
            }
            
        }
            
        /*
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_pln." Rp".$c_com->Rp($r_price_selling)." SN:".$topup_no_serial." ID : ".$gen_id_COMTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PLN TOKEN BERHASIL",$gen_message);          
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }
        */
        //
        // end   
        
            $arr_sub = array('token' => $get_token, 'nama' => $get_nama, 'tarif_daya' => $get_tarif.'/'.$get_daya,'jml_kwh' => $get_jml_kwh,'nominal' => $r_product_alias,'jml_bayar' => $c_com->Rp($r_price_selling));
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $get_token,'code' => $trace_id,'data' => $arr_sub);
            $dataupd = array('master_id' => $gen_id_COMTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
            
            $data = array('status' => TRUE,'error_code' => '600','message' => 'success','sn' => $topup_no_serial,'code' => $trace_id);
            $dataupd = array('master_id' => $gen_id_COMTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd);            
            $this->logAction('result', $trace_id, array(), 'success, running trasaction');
            $this->logAction('update', $trace_id, array(), 'Update log pulsa');
            $this->logAction('response', $trace_id, $data, '');
            $this->response($data);
                 
    }       
    public function Commerce_riwayat_post() {
        $c_com = new Commerce();
        $arr_res = array();
        //$getdata = $this->Com_model->Tgh_mutasi_pay('258','2018-01-19','2018-01-19');
        $in_agentid = $this->input->post('m_userid') ?: '';
        if(empty($in_agentid) || strlen($in_agentid) > 10 || !is_numeric($in_agentid)){            
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid m_userid',
                'data'      => array($c_com->arr_logcommerce)
            );
            $this->response($arrfin);           
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_agentid)){            
        }else{
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid agentid',
                'data'      => array($c_com->arr_logcommerce)
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
                    'price'         => $c_com->Rp($o_price),
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
                'msg'       => 'data riwayat kosong',
                'data'      => array($c_com->arr_logcommerce)
            );
        }
        
        $this->response($arrfin);
    } 
    protected function _Check_agent($in_agent_id = null,$error_code,$trace_id = ''){
        if($this->Admin_user2_model->User_by_username_nasabah($in_agent_id)){
            return;
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'sn' => '', 'code' => '');
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return false; }            
    } 
    protected function _Check_agent_data($in_agent_id = null,$error_code,$trace_id = '',$data = ''){
        if($this->Admin_user2_model->User_by_username_nasabah($in_agent_id)){             
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'agentid invalid', 'data' => $data);
        $this->logAction('response', $trace_id, $data, 'failed,agentid invalid');    
        $this->response($data);    return; }
            
    }
    protected function _Check_kdpin($in_agent_id = null, $in_kdepin = '',$error_code ='',$trace_id = ''){
        if($this->Admin_user2_model->Pin_pay_nasabah($in_agent_id,$in_kdepin)){            
        }else{ 
            $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'pin invalid', 'sn' => array(
                'sn' => ''));
        $this->logAction('response', $trace_id, $data, 'failed,pin invalid');    
        $this->response($data);    return; }            
    } 
    public function Pln_postpaid_inq_post() {
        $c_com = new Commerce(); 
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $no_meter       = $this->input->post('meterid') ? $this->input->post('meterid') : '';
        $in_agenid      = $this->input->post('m_userid') ? $this->input->post('m_userid') : '';
        
        $c_com->_Check_prm_data($no_meter,'611', $trace_id, 'meterid isempty',  '');
        $c_com->_Check_prm_data($no_meter,'613', $trace_id, 'm_userid isempty',  '');
        if (!is_numeric($no_meter)){
            $data = array('status' => FALSE,'error_code' => '612','message' => 'meterid invalid','data' => $c_com->res_result);
            $this->logAction('response', $trace_id, $data, 'failed, msisdn isnot numeric ['.$in_msisdn.']');
            $this->response($data);    
        }        
        if($this->Admin_user2_model->User_by_username_nasabah($in_agenid)){            
        }else{ 
            $c_com->_Check_prm_data('', '614', $trace_id,  'm_userid invalid', '');
        }    
        
        $res_dta_postpaid = $this->Pay_model->List_product(PROVIDER_CODE_POSPAID);
        if($res_dta_postpaid){
            foreach ($res_dta_postpaid as $val_postpaid) {
                $productid          = $val_postpaid->product_id     ? $val_postpaid->product_id : PRODUCT_CODE_POSPAID;

                $this->biaya_adm_indo     = $val_postpaid->adm_user_payment_indosis  ? $val_postpaid->adm_user_payment_indosis : 0;
                $this->biaya_adm_bmt      = $val_postpaid->adm_user_payment_bmt  ? $val_postpaid->adm_user_payment_bmt : 0;
                $this->biaya_adm_agen     = 0;
                $this->biaya_adm_prov     = $val_postpaid->adm_payment_provider  ? $val_postpaid->adm_payment_provider : 0;
            }            
              
        }else{
            $data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $c_com->res_result);
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
        //$res_call_hit_topup = $c_com->Hitget($URL);
        $res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"19010910825068349924","cust_id":"525060521079","cust_name":"YUNUS AL MARYONO","rec_payable":"1","rec_rest":[],"reffno_pln":"1919E4816002","unit_svc":[],"svc_contact":[],"tariff":[],"daya":[],"admin_fee":"1600","periode1":"201901","periode2":"","periode3":"","periode4":"","duedate":[],"mtr_date":[],"bill_amount":"59383","insentif":[],"ppn":[],"penalty":[],"lwbp_last":"00006819","lwbp_crr":"00006947","wbp_last":[],"wbp_crr":[],"kvarh_last":[],"kvarh_crr":[],"message":"Berhasil","provider":"Mitracomm"}';
        if(!$res_call_hit_topup){
            //$data = array('status' => FALSE,'error_code' => '621','message' => 'error provider','data' => $c_com->res_result);
            $this->logAction('result', $trace_id, $data, 'failed, response error third party');
             $c_com->_Check_prm_data('', '621', $trace_id,  'error provider', '');
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
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => $c_com->res_result);
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
            'tagihan'       => $c_com->Rp($this->tot_tagihan),
            'adm'           => $c_com->Rp($this->biaya_adm),            
            'total'         => $c_com->Rp($this->total),            
            'code'          => $trace_id            
        );
        $this->LogAction('info', $trace_id, $res_result, 'tagihan');
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'sucess','data' => $res_result);
        $this->LogAction('response', $trace_id, $data, '');
        $this->response($data);        
    }
    public function Pln_postpaid_pay_post() {
        $c_com = new Commerce();
        $trace_id = $this->input->post('code') ?: $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('m_userid') ? $this->input->post('m_userid') : ''; //**
        $in_pln     = $this->input->post('meterid') ? $this->input->post('meterid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : ''; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '9999';       //**
        $in_codepln = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $c_com->_Check_prm_pln_pay($in_agenid,'612',$trace_id, 'm_userid isempty');   //***
        $c_com->_Check_prm_pln_pay($in_pln,'611',$trace_id, 'meterid isempty');    // cek parameter
        //$c_com->_Check_prm_pln_pay($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $c_com->_Check_prm_pln_pay($in_codepln,'614',$trace_id, 'code isempty');       //
        //$c_com->_Check_agent_data($in_agenid,'612',$trace_id);            //***
        if($this->Admin_user2_model->User_by_username_nasabah($in_agenid)){            
        }else{ 
            $c_com->_Check_prm_pln_pay('', '612', $trace_id,  'm_userid invalid', '');
        }
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
            $c_com->_Check_prm_pln_pay($in_rkning,'617',$trace_id, 'rekening isempty');   //***            
        }else{
            $data = array('status' => FALSE,'error_code' => '618','message' => 'paytype invalid','data'=>array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        
        $res_product = $this->Pay_model->Pln_ses_by_code($in_codepln,$in_pln);
        if( ! $res_product){
            $data = array('status' => FALSE,'error_code' => '622','message' => 'code','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Pay_model->Pln_ses_by_code('.$in_codepln.','.$in_pln.')');
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
            $this->logAction('info', $trace_id, array(), 'topup_statushit ('.$this->topup_statushit.')');
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
        /*
         tess
         */
        $arrdata = array(
            'sn' => '0BAG210Z0212EE2D295D10E409D6D0DA',
            'code' => $trace_id
        );        
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
        $this->response($data);
        
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
        //$res_call_hit_topup = $this->Hitget($URL);
        
        $res_call_hit_topup = '{"status":"OK","rc":"00","partner_id":"INDOSIS","product_code":"5002","trx_id":"19010910825068349924","cust_id":"525060521079","cust_name":"YUNUS AL MARYONO","rec_payable":"1","rec_rest":"reffno_pln=1919E4816002","reffno_pln":"","unit_svc":"","svc_contact":"","tariff":"","daya":"","admin_fee":"1600","periode1":"201901","periode2":"","periode3":"","periode4":"Mitracomm","duedate":"","mtr_date":"","bill_amount":"59383","insentif":"","ppn":"","penalty":"","lwbp_last":"6819","lwbp_crr":"6947","wbp_last":"","wbp_crr":"","kvarh_last":"","kvarh_crr":"","pay_datetime":"20190109144825","footer_message":"\"Informasi Hubungi Call Center 123 Atau Hub PLN Terdekat :\"","receipt_code":"0BAG210Z0212EE2D295D10E409D6D0DA","message":"Berhasil","provider":"Mitracomm"}';
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
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $c_com->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $c_com->_Kuitansi();
              
        
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$c_com->_Tgl_hari_ini(), 
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

//        $in_addpoin = array(
//            'tid'   => $gen_id_TABTRANS_ID,
//            'agent' => $in_agenid,
//            'kode'  => PLN_POSTPAID_CODE,
//            'jenis' => 'COM',
//            'nilai' => $topup_total
//        );
//        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
//        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
//        $this->logAction('result', $trace_id, array(), 'response');
        /*
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_pln." Rp".$c_com->Rp($topup_tagihan)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PLN POSTPAID BERHASIL",$gen_message);          
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }
         
         */
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
    public function Pln_postpaid_pay2_post() {
        $c_com = new Commerce();
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('m_userid') ? $this->input->post('m_userid') : ''; //**
        $in_pln     = $this->input->post('meterid')   ? $this->input->post('meterid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : 'nasabah'; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '';       //**
        $in_codepln = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $c_com->_Check_prm_data($in_agenid,'612',$trace_id, 'agentid isempty');   //***
        $c_com->_Check_prm_data($in_pln,'611',$trace_id, 'meterid isempty');    // cek parameter
        //$c_com->_Check_prm_data($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $c_com->_Check_prm_data($in_codepln,'614',$trace_id, 'code isempty');       //
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
        }
        
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
        $topup_transid          = isset($data_topup->trx_id) ? $data_topup->trx_id : NULL;
        $topup_provider         = isset($data_topup->provider) ? $data_topup->provider : NULL;
       
        //sleep(10);
        
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
            'provider'      => $topup_provider,
            'price_selling' => $topup_tagihan,
            'price_original'=> $topup_total,
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
        
        $get_code_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR_'.$in_paytype);
        if($get_code_trans){
            foreach ($get_code_trans as $vres) {
                $res_code_kode      = $vres->kode ?: '';
                $in_desc_trans      = $vres->deskripsi ?: '';
                $res_code_tob       = $vres->tob ?: '';
            }
        }else{
            $in_desc_trans                  = $c_com->_Kodetrans_by_desc('100');
        }
        $get_mycode_trans                 = $this->Com_model->Kodetrans_by_desc_kind('BAYAR');
        if($get_mycode_trans){
            foreach ($get_mycode_trans as $v_myres) {
                $res_mycode_kode    = $v_myres->kode ?: '';
                $in_desc_trans      = $v_myres->deskripsi ?: '';
                $in_tob             = $v_myres->tob ?: '';
            }
        }else{
            $in_tob                         = $c_com->_Kodetrans_by_tob('100');
        }
        $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
        $gen_id_COMMON_ID               = $this->App_model->Gen_id();
        $gen_id_MASTER                  = $this->App_model->Gen_id();
        $in_keterangan                  = $in_desc_trans." ".$in_pln." ".  $c_com->Rp($topup_total)." SN:".$topup_no_serial;
        
        $in_KUITANSI    = $c_com->_Kuitansi();
              
        
        $arr_data = array(
            'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$c_com->_Tgl_hari_ini(), 
            //'NO_REKENING'       =>$in_rkning, 
            //'COMTYPE'           =>'PLN',
            'COMTYPE'           =>$r_type,
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
            'COMMON_ID'         =>$gen_id_COMMON_ID
            );

            
        $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);
        $this->logAction('insert', $trace_id, $arr_data, 'Tab_model->Ins_Tbl("COMTRANS")');
         if(!$res_ins){
            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Ins_Tbl("TABTRANS")');
            $this->response($data);
         }   
        
        $this->logAction('result', $trace_id, array(), 'response');
        if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $in_pln." Rp".$this->Rp($topup_tagihan)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"COM - PLN POSPAID BERHASIL",$gen_message);
           
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
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
    public function Pln_postpaid_riwayat_post() {
        $c_com = new Commerce();
        $arr_res = array();
        //$getdata = $this->Com_model->Tgh_mutasi_pay('258','2018-01-19','2018-01-19');
        $in_agentid = $this->input->post('m_userid') ?: '';
        if(empty($in_agentid) || strlen($in_agentid) > 10 || !is_numeric($in_agentid)){
            
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid m_userid',
                'data'      => array($c_com->arr_logpayment_pln)
            );
           $this->response($arrfin);           
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_agentid)){            
        }else{
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid m_userid',
                'data'      => array($c_com->arr_logpayment_pln)
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
                'msg'       => 'data riwayat kosong',
                'data'      => array($c_com->arr_logpayment_pln)
            );
        }        
        $this->response($arrfin);
    }
    protected function _Check_kdpin_data($in_agent_id = null, $in_kdepin = '',$error_code ='',$trace_id = ''){
        if(strlen($in_kdepin) == 6 ){            
        }else{
            $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'pin invalid', 'data' => '');
            $this->logAction('response', $trace_id, $data, 'failed,pin invalid');    
            $this->response($data);    return; 
        }
        
        if($this->Admin_user2_model->Pin_pay_nasabah($in_agent_id,$in_kdepin)){            
        }else{ $data = array('status' => FALSE,'error_code' => $error_code,'message' => 'pin invalid', 'data' => '');
        $this->logAction('response', $trace_id, $data, 'failed,pin invalid');    
        $this->response($data);    return; }
            
    }
    public function Bpjs_inq_post() {
        $c_com = new Commerce();
        $trace_id = $this->logid();
        $trans_id = $this->Trxid() ? $this->Trxid() : random_string('numeric', '20');
        $this->logheader($trace_id);
        $no_bpjsid = $this->input->post('bpjsid')   ? $this->input->post('bpjsid') : '';
        $in_agenid = $this->input->post('m_userid')  ? $this->input->post('m_userid') : '';
        $in_period = $this->input->post('periode')  ? $this->input->post('periode') : '';
        
        $c_com->_Check_prm_bpjs($no_bpjsid,'611', $trace_id, 'nomor bpjs isempty');
        $c_com->_Check_prm_bpjs($in_agenid,'612', $trace_id, 'agentid isempty');
        $c_com->_Check_prm_bpjs($in_period,'613', $trace_id, 'periode isempty');
        
        if (!is_numeric($no_bpjsid) || strlen($no_bpjsid) > 30 || strlen($no_bpjsid) < 5)	{
            $c_com->_Check_prm_bpjs('', '614', $trace_id,  'bpjsid invalid', '');    
        }
        if (strlen($in_period) > 5)	{
            $c_com->_Check_prm_bpjs('', '613', $trace_id,  'periode invalid', '');    
        }
        
        if($this->Admin_user2_model->User_by_username_nasabah($in_agenid)){            
        }else{ 
            $c_com->_Check_prm_bpjs('', '612', $trace_id,  'm_userid invalid', '');
        }
        
        $this->load->model('Transhoot');
        /*
        $this->logAction('info', $trace_id, array(), 'Transhoot->Bpjs_inq('.$in_agenid.','.$no_bpjsid.','.$in_period.','.$trace_id.')');
        $trans = $this->Transhoot->Bpjs_inq($in_agenid,$no_bpjsid,$in_period,$trace_id);            
        if($trans){
            $data = array('status' => TRUE,'error_code' => '600','message' => 'sucess','data' => $trans);        
            $this->LogAction('response', $trace_id, $data, '');
            $this->response($data);       
        }
        */
        $res = '{"status":true,"error_code":"600","message":"sucess","data":{"bpjsid":"0001477850905","cust_name":"SUPARMAN","periode":"02","total_person":"1","no_reff":"94990674A2FB26CA","tagihan":"80.000","adm":"2.500","total":"82.500","code":"'.$trace_id.'"}}';
        $this->response(json_decode($res));
        
//        else{
//            $data = array('status' => FALSE,'error_code' => '632','message' => 'error internal','data' => array('bpjsid' => '','cust_name' => '','periode' => '','tagihan' => '','adm' => '','total' => '','code' => ''));
//            $this->logAction('response', $trace_id, $data, 'failed, error response from transhoot->bpjsinquiry');
//            $this->response($data);
//        }
    }
    public function Bpjs_pay_post() {
        $c_com = new Commerce();
        $trace_id = $this->input->post('code') ?: $this->logid();
        $this->logheader($trace_id);
        $in_agenid  = $this->input->post('m_userid') ? $this->input->post('m_userid') : ''; //**
        $no_bpjsid     = $this->input->post('bpjsid') ? $this->input->post('bpjsid') : '';  // post data
        $in_paytype = $this->input->post('paytype') ? $this->input->post('paytype') : 'nasabah'; // value of agent or nasabah[90]     
        $in_kdepin  = $this->input->post('pin')     ? $this->input->post('pin') : '9999';       //**
        $in_codepln = $this->input->post('code')    ? $this->input->post('code') : '';       //**
        
        
        $c_com->_Check_prm_data($in_agenid,'612',$trace_id, 'm_userid isempty');   //***
        $c_com->_Check_prm_data($no_bpjsid,'611',$trace_id, 'BPJSID isempty');    // cek parameter
        //$c_com->_Check_prm_data($in_kdepin,'613',$trace_id, 'pin isempty');       //
        $c_com->_Check_prm_data($in_codepln,'614',$trace_id, 'code isempty');       //

        if($this->Admin_user2_model->User_by_username_nasabah($in_agenid)){            
        }else{ 
            $c_com->_Check_prm_bpjs('', '612', $trace_id,  'm_userid invalid', '');
        }
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
            $c_com->_Check_prm_data($in_rkning,'617',$trace_id, 'rekening isempty');   //***            
        }
        else{
            $data = array('status' => FALSE,'error_code' => '618','message' => 'paytype invalid','data'=>array('sn' => '','code' => ''));
            $this->logAction('response', $trace_id, $data, 'failed, paytype invalid ['.$in_paytype.']');
            $this->response($data); 
        }
        
        $this->logAction('info', $trace_id, array(), 'norekening ('.$in_paytype.') =>'.$in_rkning);
        $this->logAction('info', $trace_id, array(), 'Transhoot->Bpjs_pay('.$in_agenid.','.$no_bpjsid.','.$trace_id.','.$in_rkning.','.$in_paytype.')');            
        $this->CTStart();
        /*$this->load->model('Transhoot');
        $trans = $this->Transhoot->Bpjs_pay($in_agenid,$no_bpjsid,$trace_id,$in_rkning,$in_paytype);
        if($trans){
            if(is_array($trans)){
                foreach ($trans as $v) {
                    $topup_tagihan       = $v['tagihan'];
                    $topup_no_serial    = $v['sn'];
                    $gen_id_TABTRANS_ID = $v['tabtransid'];
                }
            }
        }else{ */
//            $data = array('status' => FALSE,'error_code' => '633','message' => 'error provider','data'=>array('sn' => '','code' => ''));
//            $this->logAction('response', $trace_id, $data, 'failed, response failed ['.$trans.']');
//            $this->response($data); 
       /*     return;
        }*/
        
        $this->logAction('result', $trace_id, array(), 'response');
        /*if($this->CTEnd() > EXPTIMEINFO){
            $url = '';
            $gen_message = $no_bpjsid." Rp".$c_com->Rp($topup_tagihan)." SN:".$topup_no_serial." ID : ".$gen_id_TABTRANS_ID;
            $url .= "?title=Commerce&email=".$agn_username."&tid=".$trace_id."&message=".urlencode($gen_message);
            $this->logAction('transaction', $trace_id, array(), 'url :'.$url);            
            
            include 'Fcm.php';
            $fcm = new Fcm();
            $getsingle = $fcm->SendSinglePush_priv($trace_id,$agn_username,"PAY - Pembayaran BPJS BERHASIL",$gen_message);          
            $this->logAction('transaction', $trace_id, array(), 'response : '.$getsingle);
        }*/
        $topup_no_serial = '94990674A2FB26CA';
        $arrdata = array(
            'sn' => $topup_no_serial,
            'code' => $trace_id,            
        );        
        
        $data = array('status' => TRUE,'error_code' => '600','message' => 'success','data' => $arrdata);
        //$dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'last_status= hit provider berhasil and proses transaction done');
        //$this->logAction('info', $trace_id, $dataupd, 'upd');
        //$this->Pay_model->Upd_logpayment($trace_id,$dataupd);          
        
        $this->logAction('result', $trace_id, array(), 'success, running trasaction');
        $this->logAction('update', $trace_id, array(), 'Update log pulsa');
        $this->logAction('info ', $trace_id, $arrdata, 'serial number : ');
        $this->logAction('response', $trace_id, $data, '');
        $this->response($data);                
    }    
    public function Bpjs_riwayat_post() {
        $c_com = new Commerce();
        $arr_res = array();
        //$getdata = $this->Com_model->Tgh_mutasi_pay('258','2018-01-19','2018-01-19');
        $in_agentid = $this->input->post('m_userid') ?: '';
        if(empty($in_agentid) || strlen($in_agentid) > 10 || !is_numeric($in_agentid)){
            
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid m_userid',
                'data'      => array($c_com->arr_logpayment_bpjs)
            );
            $this->response($arrfin);           
        }
        if($this->Admin_user2_model->User_by_username_nasabah($in_agentid)){            
        }else{
            $arrfin = array(
                'errorcode' => '101',
                'msg'       => 'invalid m_userid',
                'data'      => array($c_com->arr_logpayment_bpjs)
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
                    'periode'       => $o_periode1,
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
                'msg'       => 'data riwayat kosong',
                'data'      => array($c_com->arr_logpayment_bpjs)
            );
        }        
        $this->response($arrfin);
    }
    
}
