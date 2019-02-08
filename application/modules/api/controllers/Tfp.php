<?php

require_once 'Transfer.php'; 

define('setor_my_kode_trans', '100');
define('setor_kode_trans', '137');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tfp
 *
 * @author edisite
 */
class Tfp extends API_Controller{
    //put your code here
    protected $resinq = [
        'rekening'          => '',
        'nama_cust'          => '',
        'reference_number'  => '',
        'booking_id'        => '',
        'product_id'        => ''
    ];
    private $no_rekening_bmt;




    public function __construct() {
        parent::__construct();
    }
    public function Inq_post() {
        $ctf = new Transfer();
         
//                "status": "reqinqpayment",
//                "vaid": "001123456",
//                "booking_datetime": "2018-12-14 16:55:03",
//                "reference_number": "204", // transasction id dr agus
//                "booking_id": "5829992102381", // transaction id / angka random dari opik
//                "product_id": "103" // dari opik.. in dok aj
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $respondata=json_decode(file_get_contents('php://input'),TRUE);
        if (empty($respondata))
        {            
            $this->_Inqcekvar('','107', $trace_id, 'parameter invalid');
        }
        $this->LogAction('Parameter', $trace_id, array(), file_get_contents('php://input'));
        
//       begin  check json
        if($ctf->isJSON(file_get_contents('php://input')) === false){
            $this->_Inqcekvar('','107', $trace_id, 'parameter invalid');;
        }
        
        $rekening   = $respondata['vaid'] ?: '';
        $ref_number = $respondata['reference_number'] ?: '';
        $booking_id = $respondata['booking_id'] ?: '';
        $product_id = $respondata['product_id'] ?: '';
        $reqstatus  = $respondata['status'] ?: '';
        
        $this->_Inqcekvar($rekening,'101',$trace_id, 'rekening kosong');   //***
        $this->_Inqcekvar($ref_number,'102', $trace_id, 'reference_number kosong');
        $this->_Inqcekvar($booking_id,'103', $trace_id, 'booking_id kosong');
        $this->_Inqcekvar($product_id,'104', $trace_id, 'product_id kosong');
        $this->_Inqcekvar($reqstatus,'105', $trace_id, 'status invalid');
        if($reqstatus == "resinqpayment"){            
        }else{
            $this->_Inqcekvar('','105', $trace_id, 'status invalid');
        }
        
        $this->logAction('req', $trace_id, $arr, 'Nas_model->Tfp_tab_src_nas('.$rekening.')');
        $getrek = $this->Nas_model->Tfp_tab_src_nas($rekening);
        $this->logAction('res', $trace_id, $getrek, '');
        if($getrek){
            foreach ($getrek as $v) {                
                $nama =  $v->nama ?: '';
            }            
            if(empty($nama)){
                $this->logAction('info', $trace_id, array(), 'error: nama dari rekening tersebut kosong');
                $this->_Inqcekvar('','106',$trace_id, 'rekening tidak ditemukan');   //***
            }else{
                $arr = array(
                        'error_code'        => 100,
                        'message'           => 'sukses',
                        'rekening'          => $rekening,
                        'nama_cust'         => $nama,
                        'reference_number'  => $ref_number,
                        'booking_id'        => $booking_id,
                        'product_id'        => $product_id,
                    );
                $this->logAction('response', $trace_id, $arr, 'ok');
                $this->response($arr);
            }
        }else{
            $this->logAction('info', $trace_id, array(), 'error: tidak ada data tidak ditemukan');
            $this->_Inqcekvar('','106',$trace_id, 'rekening tidak ditemukan');   //***            
        }        
    }
//7080120652
    protected function _Inqcekvar($var = '',$errorcode = '',$trace_id = '',$field = '') {
        if(empty($var) || strlen($var) > 50){
            $arr_res   = array('error_code' => $errorcode,'message' => $field);
            $arr_res = array_merge($arr_res,  $this->resinq);
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);            
        }
    }
    public function Paynotif_post() {
        $ctf = new Transfer();
        
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_codetransfer = 102;
        $in_agentid      = '000';
        
        $respondata=json_decode(file_get_contents('php://input'),TRUE);
        if (empty($respondata))
        {
            $this->aresp = array('status' => 'resnotification','ack'=>'01','booking_id'=>'00');
            $this->LogAction('Response', $trace_id, $this->aresp, 'ignored');
            $this->Logend($trace_id);
            $this->response($this->aresp);
        }
        $this->LogAction('Parameter', $trace_id, array(), file_get_contents('php://input'));
        
//       begin  check json
        if($ctf->isJSON(file_get_contents('php://input')) === false){
            $this->aresp = array('status' => 'resnotification','ack'=>'01','booking_id'=>'00');
            $this->LogAction('Response', $trace_id, $this->aresp, 'ignored');
            $this->Logend($trace_id);
            $this->response($this->aresp);
        }        
        $recacc_status                      = $respondata['status'] ?: '';
        $recacc_booking_id                  = $respondata['booking_id'] ?: '';
        $sender_no_rekening                 = $respondata['account_destination'] ?: '';
        $recdest_customer_name              = $respondata['customer_name'] ?: '';
        $recdest_issuer_name                = $respondata['issuer_name'] ?: '';
        $recdest_issuer_bank                = $respondata['issuer_bank'] ?: '';
        $recdest_reference_number           = $respondata['reference_number'] ?: '';
        $recdest_trx_date                   = $respondata['trx_date'] ?: '';
        $recdest_trx_id                     = $respondata['trx_id'] ?: '';
        $gsender_nominal                    = $respondata['amount'] ?: '';
        
        $this->_NotifRes($recacc_booking_id, '101', $trace_id, 'booking_id failed');
        $this->_NotifRes($sender_no_rekening, '102', $trace_id, 'account_destination failed');
        $this->_NotifRes($recdest_customer_name, '103', $trace_id, 'customer_name failed');
        $this->_NotifRes($recdest_issuer_bank, '104', $trace_id, 'issuer_bank failed');
        $this->_NotifRes($recdest_reference_number, '105', $trace_id, 'reference_number failed');
        $this->_NotifRes($recdest_trx_id, '106', $trace_id, 'trx_id failed');
        $this->_NotifRes($gsender_nominal, '107', $trace_id, 'amount failed');
        
        $this->logAction('req', $trace_id, array(), 'Nas_model->Tfp_tab_src_nas('.$sender_no_rekening.')');
        $getrek = $this->Nas_model->Tfp_tab_src_nas($sender_no_rekening);
        $this->logAction('res', $trace_id, $getrek, '');
        if($getrek){
            foreach ($getrek as $v) {                
                $this->no_rekening_bmt  =  $v->rekening ?: '';    
                $greceiver_name         =  $v->nama ?: '';    
            }          
            
        }else{
            $this->logAction('info', $trace_id, array(), 'error: tidak ada data tidak ditemukan');
            $this->_NotifRes('','106',$trace_id, 'rekening tidak ditemukan');   //***            
        }
        
        $in_kode_bank_sender     = $this->Trf_model->Cek_bank_default();
        
        $res_call_jenis_trf = $this->Trf_model->Model_transfer($in_codetransfer);
        $this->logAction('select', $trace_id, $res_call_jenis_trf, 'Trf_model->Model_transfer('.$in_codetransfer.')');
        if($res_call_jenis_trf){
            foreach ($res_call_jenis_trf as $sub_jenis_trf) {                
                $set_adm_default    = $sub_jenis_trf->set_adm_default;
                $gsender_cost_adm        = $sub_jenis_trf->adm_default;
                $set_limit          = $sub_jenis_trf->set_limit;
                $min_trf            = $sub_jenis_trf->min_trf;
                $max_trf            = $sub_jenis_trf->max_trf;
                $gsender_cost_adm_bmt       = $sub_jenis_trf->adm_bmt;
            }
        }else{
            $this->logAction('response', $trace_id, array(), 'Failed / codetransfer not in database');            
            $this->_NotifRes('','106',$trace_id, 'codetransfer');   //*** 
        }
        
        if(empty($gsender_cost_adm_bmt)){
            $gsender_cost_adm_bmt   = 2500;
        }
            
        
        $cost_adm_transfer = $gsender_cost_adm;
        $this->logAction('check cost adm', $trace_id, array('biaya adm' => $cost_adm_transfer), 'set by default');
            
        
        
        $datas = $this->_setor($this->no_rekening_bmt, $gsender_nominal , 0, $in_agentid, $recdest_issuer_name,$recdest_issuer_bank,$trace_id,'dsb');
        //$datas = '';
        $this->logAction('_setor', $trace_id,array(), '_tarik('.$this->no_rekening_bmt.','.$gsender_nominal .','. '0'.','.$in_agentid.', '.$recdest_issuer_name.','.$recdest_issuer_bank.','.$trace_id.',dsb)');
        $this->LogAction('_setor', $trace_id, array(), 'resp '.$datas);
        
        if($datas){            
            $vs = json_decode($datas,true);
            if($vs['status'] == true){
                $masterid_sender = $vs['masterid']; 
            }else{
                $masterid_sender  = '';
                $this->App_model->TrfErrMsg($trace_id,$datas,'error rekening sender _tarik()','pengambilan saldo transfer',$this->uri->uri_string());
            }
        }else{
            $masterid_sender  = '';
            $this->App_model->TrfErrMsg($trace_id,$datas,'error rekening sender _tarik()','pengambilan saldo transfer',$this->uri->uri_string());
        }
        
        $datasr = $this->_tarik_dsb($this->no_rekening_bmt,$gsender_cost_adm, $gsender_cost_adm_bmt, $in_agentid, $recdest_issuer_name,$recdest_issuer_bank,$trace_id);
        //$datasr = '';
        $this->logAction('_tarik_dsb', $trace_id,array(), '_tarik('.$this->no_rekening_bmt.','.$gsender_cost_adm .','. $gsender_cost_adm_bmt.','.$in_agentid.', '.$recdest_issuer_name.','.$recdest_issuer_bank.','.$trace_id.')');
        $this->LogAction('_tarik_dsb', $trace_id, array(), 'resp '.$datasr);
        if($datasr){
            $vr = json_decode($datasr,true); 
            if($vr['status'] ==  true){
                $masterid_receiver = $vr['masterid'];  
            }else{
                $masterid_receiver = '';                 
                $this->App_model->TrfErrMsg($trace_id,$datasr,'error rekening receiver _tarik_dsb()','pemindahan saldo transfer',$this->uri->uri_string());
            }            
        }else{
            $masterid_receiver  = '-';
            $this->App_model->TrfErrMsg($trace_id,$datasr,'error rekening receiver _tarik_dsb()','pemindahan saldo transfer',$this->uri->uri_string());
        } 
        $gsender_total = $gsender_nominal + $cost_adm_transfer + $gsender_cost_adm_bmt;
        $arr_insert = array(
            'user_id'           => $in_agentid, 
            'dtm'               => date('Y-m-d H:i:s'), 
            'kode_transfer'     => '102', 
            'rekening_sender'   => '', 
            'kode_bank_sender'  => '', 
            'nama_sender'       => $recdest_issuer_name, 
            'nama_bank_sender'  => $recdest_issuer_bank, 
            'rekening_receiver' => $this->no_rekening_bmt, 
            'kode_bank_receiver'=> $in_kode_bank_sender, 
            'nama_receiver'     => $greceiver_name, 
            'nominal'           => $gsender_nominal, 
            'cost_adm'          => $gsender_cost_adm, 
            'total'             => $gsender_total, 
            'master_id_sender'  => $masterid_sender, 
            'master_id_receiver'=> $masterid_receiver, 
            'ip'                => $this->input->ip_address(), 
            'last_upd'          => date('Y-m-d H:i:s'), 
            'proses_type'       => 'payment', 
            'd_response'        => file_get_contents('php://input'), 
            'd_transaction_id'  => $recdest_trx_id, 
            'cost_adm_bmt'      => $gsender_cost_adm_bmt, 
            'chaneltype'        => 'api', 
            'userstype'         => ''
        );  
        
        $getinsert = $this->Tab_model->Ins_Tbl('transfer_log_tfp',$arr_insert);
        $this->LogAction('insert', $trace_id, $arr_insert, 'Tab_model->Ins_Tbl(transfer_log_tfp) : res:'.$getinsert);
        
        $arr_res   =array('status' => 'resnotification','ack'=>'00','booking_id'=>$recacc_booking_id);
        $this->logAction('response', $trace_id,$arr_res, 'Transaction is Done');
        $this->response($arr_res);   

    }
    protected function _NotifRes($var = '',$errorcode = '',$trace_id = '',$field = '') {
        if(empty($var) || strlen($var) > 50){
            $arr_res   =array('status' => 'resnotification','ack'=>'01','booking_id'=>'00');
            $this->logAction('response', $trace_id, $arr_res, 'failed,'.$field);
            $this->response($arr_res);            
        }
    }
    public function _setor($in_NO_REKENING = '',$in_nominal = 0,$in_adm = 0,$in_USERID = '',$in_rek_receiver = '',$in_kode_bank_receiver = '',$trace_id = '', $tipetrf = '') {

        $ctf = new Transfer();         
        $trace_id   = $trace_id ?: $this->logid();
        //$this->logheader($trace_id);
        if(empty($in_NO_REKENING) || empty($in_nominal) || empty($in_USERID)){
            return FALSE;
        }
        $in_kd_kantor           = kode_kantor_default;
        $in_adm_penutupan       = '';       
        $in_SANDI_TRANS         = setor_sandi;       
        $in_kd_trans            = setor_kode_trans;
        $in_mykdetrans          = setor_my_kode_trans;
        $in_ver                 = veririfikasi;
        $in_KODE_KOLEKTOR       = kode_kolektor;
        
        $in_POKOK = $in_nominal + $in_adm; // uang transfer + biaya adm
                                            // $in_pokok = nilai uang potong rekening
        
//        $in_DEBET100   = 0 ;
//        $in_KREDIT100  = $in_POKOK;
//        $in_DEBET200   = $in_nominal ;
//        $in_KREDIT200  = 0;
        
        $res_cek_rek = $this->Tab_model->Tab_nas($in_NO_REKENING);
        if(!$res_cek_rek){
            $data = array('status' => FALSE,'message' => 'no rekening notfound in tabung', 'masterid' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_NO_REKENING.')');            
            return json_encode($data);;
        }
        
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        //cek saldo 
        if(abs($in_POKOK) > abs($cek_saldo_akhir)){
            $data = array('status' => FALSE,'message' => 'saldo insufficent balance', 'masterid' => '');
            $this->logAction('response', $trace_id, $data, 'saldo insufficent balance');            
            return json_encode($data);  
        }
        
        $gen_id_TABTRANS_ID = $this->App_model->Gen_id();
        $gen_id_COMMON_ID   = $this->App_model->Gen_id();
        $gen_id_MASTER      = $this->App_model->Gen_id();
        $in_KUITANSI        = $ctf->_Kuitansi();
        $in_keterangan      = $ctf->_Kodetrans_by_desc($in_kd_trans);
        $in_tob             = $ctf->_Kodetrans_by_tob($in_kd_trans);
        $in_keterangan      = $in_keterangan." : dr ".$in_kode_bank_receiver." ".$in_rek_receiver." ".  $ctf->Rp($in_POKOK);
        $unitkerja          = $in_kd_kantor;
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$ctf->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_NO_REKENING, 
            'MY_KODE_TRANS'     =>$in_mykdetrans, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$in_POKOK,
            'ADM'               =>'',
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
                $data = array('status' => FALSE,'message' => 'Gagal transaction', 'masterid' => '');
                $this->logAction('response', $trace_id, $data, 'failed, insert unsuccessful');
                //$this->response($data);
                return json_encode($data);
            }
            
            $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
            $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);

            $res_call_tab_trans = $this->Tab_model->Tab_trans($gen_id_TABTRANS_ID);
            $this->logAction('select', $trace_id, array(), 'Tab_model->Tab_trans('.$gen_id_TABTRANS_ID.')');
            if(!$res_call_tab_trans){
                $data = array('status' => FALSE,'message' => 'Gagal transaction', 'masterid' => '');
                $this->logAction('response', $trace_id, $data, 'failed, select unsuccessful');
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('response', $trace_id, $data, 'failed, update unsuccessful');
                //$this->response($data);
                return json_encode($data);
            }

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
            }else{
                $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
                $this->logAction('result', $trace_id, array('kode_perk_kas' => $in_kode_perk_kas), 'kode_perk_kas set default');
                
            }
            if(empty($in_kode_perk_kas)){
                $in_kode_perk_kas = 0;
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
                $in_kode_perk_transfer                  =  $sub_call_tab_integrasi_by_kd->KODE_PERK_TRANSFER; 
            }
            $this->logAction('result', $trace_id, $res_call_tab_integrasi_by_kd, 'kode integrasi');
            $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total;
            }

            $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
            foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
                $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: 'TAB';
            }
            $this->logAction('kodejurnal', $trace_id, array(), 'kode jurnal = '.$res_kode_jurnal);
            $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $ctf->_Tgl_hari_ini(), 
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
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, // simara
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_nominal                                                            
                ),  
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, // kas kasanah
                    'DEBET'         =>  $in_POKOK, 
                    'KREDIT'        =>  0
                )  
            );
            
            $ar_trans_detail_dsb = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_POKOK
                ),     
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  KODEPERK_ANRO_DEFAULT, 
                    'DEBET'         =>  $in_POKOK, 
                    'KREDIT'        =>  0                                                             
                )                           
            );
            
            $ar_trans_detail_adm = array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_transfer ?: KODEPERKTRANSFER_DEFAULT, 
                    'DEBET'         =>  0,
                    'KREDIT'        =>  $in_adm ?: 0
                );
            
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
            $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
            if($res_run){                
                if(abs($in_adm) > 0){
                    $this->Tab_model->Ins_batch('transaksi_detail',$ar_trans_detail_adm);
                    $this->logAction('transaction', $trace_id, $ar_trans_detail_adm, 'TRANSAKSI_DETAIL ADM');
                }
                if($tipetrf == "dsb"){
                    $this->Tab_model->Ins_batch('transaksi_detail',$ar_trans_detail_dsb);
                    $this->logAction('transaction', $trace_id, $ar_trans_detail_dsb, 'TRANSAKSI_DETAIL DSB');
                }                
                $data = array('status' => TRUE,'masterid' => $gen_id_MASTER,'message' => 'success');
                $this->logAction('result', $trace_id, array(), 'success, running trasaction');
                $this->logAction('response', $trace_id, $data, '');
                //$this->response($data);
                return json_encode($data);                
            }else{            
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $data = array('status' => FALSE,'message' => 'Gagal transaction', 'masterid' => '');
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
                $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
                $this->logAction('response', $trace_id, $data, '');
                //$this->response($data);
                return json_encode($data);                
            }       
    }
    public function _tarik_dsb($in_NO_REKENING = '',$in_nominal = 0,$in_adm = 0,$in_USERID = '',$in_rek_receiver = '',$in_kode_bank_receiver = '',$trace_id = '') {
        $ctf = new Transfer();                 
        $trace_id   = $trace_id ?: $this->logid();
        //$this->logheader($trace_id);
        if(empty($in_NO_REKENING) || empty($in_nominal) || empty($in_USERID) || empty($in_adm)){
            return FALSE;
        }
        $in_kd_kantor           = kode_kantor_default;
        $in_adm_penutupan       = '';       
        $in_SANDI_TRANS         = setor_sandi;       
        $in_kd_trans            = "236";
        $in_mykdetrans          = "200";
        $in_ver                 = veririfikasi;
        $in_KODE_KOLEKTOR       = kode_kolektor;
        
        $in_POKOK = $in_nominal + $in_adm; // uang transfer + biaya adm
                                            // $in_pokok = nilai uang potong rekening
        
        $res_cek_rek = $this->Tab_model->Tab_nas($in_NO_REKENING);
        if(!$res_cek_rek){
            $data = array('status' => FALSE,'message' => 'no rekening notfound in tabung', 'masterid' => '');
            $this->logAction('response', $trace_id, $data, 'failed, Tab_model->Tab_nas('.$in_NO_REKENING.')');            
            return json_encode($data);;
        }
        
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
            $cek_nasabah_id   = $sub_cek_rek->nasabah_id;
            $cek_saldo_akhir   = $sub_cek_rek->saldo_akhir;
        }
        
        
        $gen_id_TABTRANS_ID = $this->App_model->Gen_id();
        $gen_id_COMMON_ID   = $this->App_model->Gen_id();
        $gen_id_MASTER      = $this->App_model->Gen_id();
        $in_KUITANSI        = $ctf->_Kuitansi();
        $in_keterangan      = $ctf->_Kodetrans_by_desc($in_kd_trans);
        $in_tob             = $ctf->_Kodetrans_by_tob($in_kd_trans);
        $in_keterangan      = $in_keterangan." dr ".$in_kode_bank_receiver." ".$in_rek_receiver." ".  $ctf->Rp($in_POKOK);
        $unitkerja          = $in_kd_kantor;
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$ctf->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$in_NO_REKENING, 
            'MY_KODE_TRANS'     =>$in_mykdetrans, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$in_POKOK,
            'ADM'               =>'',
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
                $data = array('status' => FALSE,'message' => 'Gagal transaction', 'masterid' => '');
                $this->logAction('response', $trace_id, $data, 'failed, insert unsuccessful');
                //$this->response($data);
                return json_encode($data);
            }
            
            $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
            $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);

            $res_call_tab_trans = $this->Tab_model->Tab_trans($gen_id_TABTRANS_ID);
            $this->logAction('select', $trace_id, array(), 'Tab_model->Tab_trans('.$gen_id_TABTRANS_ID.')');
            if(!$res_call_tab_trans){
                $data = array('status' => FALSE,'message' => 'Gagal transaction', 'masterid' => '');
                $this->logAction('response', $trace_id, $data, 'failed, select unsuccessful');
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('response', $trace_id, $data, 'failed, update unsuccessful');
                //$this->response($data);
                return json_encode($data);
            }           
            
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
            }else{
                $in_kode_perk_kas = KODEPERKKAS_DEFAULT;
                $this->logAction('result', $trace_id, array('kode_perk_kas' => $in_kode_perk_kas), 'kode_perk_kas set default');
                
            }
            if(empty($in_kode_perk_kas)){
                $in_kode_perk_kas = 0;
            }
            
            $res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($in_kode_integrasi);
            $this->logAction('select', $trace_id, array(), 'kode integrasi > Tab_model->Integrasi_by_kd_int('.$in_kode_integrasi.')');
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_hutang_pokok_in_integrasi =  $sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
                $in_kode_perk_pend_adm_in_integrasi     =  $sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;      
                $in_kode_perk_transfer                  =  $sub_call_tab_integrasi_by_kd->KODE_PERK_TRANSFER; 
            }
            
            $res_kode_jurnal = "TAB";
            $this->logAction('kodejurnal', $trace_id, array(), 'kode jurnal = '.$res_kode_jurnal);
            $arr_master = array(
                'TRANS_ID'          =>  $gen_id_MASTER, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $ctf->_Tgl_hari_ini(), 
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
                    'KODE_PERK'     =>  $in_kode_perk_hutang_pokok_in_integrasi, // simara
                    'DEBET'         =>  $in_POKOK, 
                    'KREDIT'        =>  0                                                             
                ),  
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  KODEPERKKAS_DEFAULT, // kas kasanah
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_POKOK
                ),  
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  KODEPERKKAS_DEFAULT, 
                    'DEBET'         =>  $in_POKOK, 
                    'KREDIT'        =>  0
                ),
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  KODEPERK_ANRO_DEFAULT, 
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_nominal                                                             
                ),                
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  KODEPERKTRANSFER_DEFAULT, 
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_adm ?: 0
                )
            );
            
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            $this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
            $this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
            if($res_run){                                
                $data = array('status' => TRUE,'masterid' => $gen_id_MASTER,'message' => 'success');
                $this->logAction('result', $trace_id, array(), 'success, running trasaction');
                $this->logAction('response', $trace_id, $data, '');
                //$this->response($data);
                return json_encode($data);                
            }else{            
                $this->Tab_model->Del_by_tabtrans($gen_id_TABTRANS_ID);            
                $data = array('status' => FALSE,'message' => 'Gagal transaction', 'masterid' => '');
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('update', $trace_id, array(), 'update saldo : Tab_model->Saldo_upd_by_rekening('.$in_NO_REKENING.')');
                $this->Tab_model->Saldo_upd_by_rekening($in_NO_REKENING);
                $this->logAction('response', $trace_id, $data, '');
                //$this->response($data);
                return json_encode($data);                
            }       
    }
    
}
