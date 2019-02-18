  <?php
define('TRANSFER_LOCAL_KIRIM_CODE', '225');
define('TRANSFER_LOCAL_KIRIM_MYCODE', '200');
define('TRANSFER_LOCAL_TERIMA_CODE', '125');
define('TRANSFER_LOCAL_TERIMA_MYCODE', '100');
define('KODE_KANTOR_DEFAULT', '35');
define('ADM_DEFAULT', '0.00');
define('KODEPERKKAS_DEFAULT', '10101');
define('KODEPERKTRANSFER_DEFAULT', '4091'); // admin biaya transfer
define('KODEPERK_ANRO_DEFAULT', '10208');
define('tarik_my_kode_trans', '200');
define('tarik_kode_trans', '226');
define('setor_sandi', '01');
define('tarik_sandi', '02');
define('veririfikasi', '1');
define('kode_kolektor', '000');
define('kode_kantor_default', '35');
define('transfer_adm_bmt_default', '3000');


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transfer
 *
 * @author edisite
 */
class Transfer extends API_Controller {
    //put your code here
    private $nama_bank = '';
    
    public $res_trf_disburment_riwayat =[ 
            'transferdate'           => '',
            'sender_kodebank'        => '',
            'sender_namebank'        => '',
            'sender_rekening'        => '',
            'sender_name'            => '',
            'receiver_kodebank'      => '',
            'receiver_namabank'      => '',
            'receiver_rekening'      => '',
            'receiver_name'          => '',
            'nominal_transfer'       => '',                 
            'biaya_admin_transfer'   => '',                 
            'total'                  => '',                 
            'msg_transfer'        => '',    
        ];
    public $arrtrf = ['tstan' => '','tdtm' => ''];
    
    public function __construct() {
        parent::__construct();
        //date_default_timezone_set('Asia/Jakarta');
               
    }
    public function Res_cek_rek($errorcode = '',$message = '') {
        $arr = array(
                'status'                    => FALSE,
                'errorcode'                 => $errorcode,
                'message'                   => $message,
                'sender_no_rekening'        => '',
                'sender_nama_nasabah'       => '',
                'sender_kode_bank'          => '',
                'receiver_no_rekening'      => '',
                'receiver_nama_nasabah'     => '',
                'receiver_kode_bank'        => '',
                'receiver_alamat'           => '',
                'nominal'                   => '',
                'adm'                       => '',
                'total'                     => '',
                'code'                      => ''
              );   
        $this->response($arr);
    }
    public function Res_riwayat($errorcode = '',$message = '') {
        $data = array();
        $data[] = array(
                                                    'bank_sender'=> '',
                                                    'rek_sender' => '',
                                                    'nama_sender' => '',
                                                    'bank_receiver'=> '',
                                                    'rek_receiver'=> '',
                                                    'nama_receiver'=> '',
                                                    'nominal' => '',
                                                    'status'=> '',
                                                    'codesave'=> ''
                                                );   
        $arr = array(
                'status'                    => FALSE,
                'errorcode'                 => $errorcode,
                'message'                   => $message,
                'list'                      => $data
              );   
        $this->response($arr);
    }
    public function Check_rekening_post() {
        $traceid = $this->logid();
        $this->logheader($traceid);
        $this->_Check_postdata();
        $in_agent_id                = $this->input->post('agentid') ?: '';
        $in_rek_sender              = $this->input->post('rek_sender') ?: '';
        $in_rek_receiver            = $this->input->post('rek_receiver') ?: '';
        $in_codetransfer            = $this->input->post('codetransfer') ?: '';               
        $in_nominal                 = $this->input->post('nominal') ?: '';        
        
        //----------********-------
                
        $cost_adm_transfer = 0;
        $masterid   = '';
        if(empty($in_agent_id)){            
            $this->logAction('response', $traceid, array(), 'Failed / data agent is empty');
            $this->Res_cek_rek('104', 'agent invalid');
        }
        if(empty($in_nominal)){            
            $this->logAction('response', $traceid, array(), 'Failed / nominal is empty');
            $this->Res_cek_rek('101', 'nominal isempty');
        }
        if(empty($in_rek_sender)){            
            $this->logAction('response', $traceid, array(), 'Failed / rek sender is empty');
            $this->Res_cek_rek('102', 'rek sender isempty');
        }
        if(empty($in_rek_receiver)){            
            $this->logAction('response', $traceid, array(), 'Failed / rek receiver is empty');
            $this->Res_cek_rek('103', 'rek receiver isempty');
        }
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / data agent not found in databases');
            $this->Res_cek_rek('105', 'agent invalid');
        }

        if($in_codetransfer == "100"){ //local antar rek bmt
            $res_kode_bank_transfer     = $this->Trf_model->Cek_bank_default();
            $in_kode_bank_sender        = $res_kode_bank_transfer;
            $in_kode_bank_receiver      = $res_kode_bank_transfer;
        }elseif($in_codetransfer == "101"){ // bmt ke bank lain
            $res_kode_bank_transfer     = $this->Trf_model->Cek_bank_default();
            $in_kode_bank_sender        = $res_kode_bank_transfer;
            $receiver_nama_nasabah      = $this->input->post('nama_nasabah_receiver');
            $receiver_alamat            = $this->input->post('alamat_receiver');
            $in_kode_bank_receiver      = $this->input->post('kode_bank_receiver');
            
        }elseif($in_codetransfer == "102"){ // bank lain ke bmt
            $res_kode_bank_transfer     = $this->Trf_model->Cek_bank_default();
            $in_kode_bank_sender        = $this->input->post('kode_bank_sender');
            $sender_nama_nasabah        = $this->input->post('nama_nasabah_sender');
            $sender_alamat              = $this->input->post('alamat_sender');            
            $in_kode_bank_receiver      = $res_kode_bank_transfer;
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not recognized');
            $this->Res_cek_rek('106', 'codetransfer invalid');
        }
        $res_call_jenis_trf = $this->Trf_model->Model_transfer($in_codetransfer);
        $this->logAction('select', $traceid, $res_call_jenis_trf, 'Trf_model->Model_transfer('.$in_codetransfer.')');
        if($res_call_jenis_trf){
            foreach ($res_call_jenis_trf as $sub_jenis_trf) {                
                $set_adm_default    = $sub_jenis_trf->set_adm_default;
                $adm_default        = $sub_jenis_trf->adm_default;
                $set_limit          = $sub_jenis_trf->set_limit;
                $min_trf            = $sub_jenis_trf->min_trf;
                $max_trf            = $sub_jenis_trf->max_trf;
            }
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not in database');
            $this->Res_cek_rek('106', 'codetransfer invalid');
        }
        //$this->logAction('Trf_model->Model_transfer',$res_call_jenis_trf,'OK');
        
            
        if(strtolower($set_adm_default) == "bydefault"){
            $cost_adm_transfer = $adm_default;
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by default');
            
        }elseif(strtolower($set_adm_default) == "byreceiverbank"){
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_receiver);
            if($res_cek_bank){
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }            
            }
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by byreceiverbank');
        }elseif(strtolower($set_adm_default) == "bysenderbank"){
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_sender);
            if($res_cek_bank){
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }            
            }
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by bysenderbank');
        }
        $this->logAction('result', $traceid, array(), 'batas min :'.$min_trf);
        $this->logAction('result', $traceid, array(), 'batas max :'.$max_trf);
        if(strtolower($set_limit) == "yes"){ // cek setting limit
            if($in_nominal < $min_trf){ // limit nominal transfer
                $this->logAction('result', $traceid, array(), 'The nominal limit to be transferred exceeds the limit :nominal :'.$in_nominal);              
                $this->Res_cek_rek('111', 'nominal min transfer '.$min_trf);
            }
            if($in_nominal > $max_trf){ // limit nominal transfer
                $this->logAction('result', $traceid, array(), 'The nominal limit to be transferred exceeds the limit :nominal :'.$in_nominal);
                $this->Res_cek_rek('112', 'nominal max transfer '.$max_trf);
            }
        }
            
        //$arr = array();
        $tarif_code         = "";
        $in_status_session  = 0;
        $rmasterid          = "";
        if($in_codetransfer == "100"){
            $datasender     = $this->_Cekrek($in_rek_sender);
            if($datasender){
                $sub_res_sender = $this->_Jdecode($datasender);
                $sender_no_rekening         = $sub_res_sender->norekening;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->saldo;             
                $sender_alamat              = $sub_res_sender->alamat;  
                $SENDERSTATUS               = $sub_res_sender->status;  
                $sender_errorstatus                = $sub_res_sender->message;
            }
            $datareceiver   = $this->_Cekrek($in_rek_receiver);
            if($datareceiver){
                $sub_res_receiver = $this->_Jdecode($datareceiver);
                $receiver_no_rekening         = $sub_res_receiver->norekening;                
                $receiver_nama_nasabah        = $sub_res_receiver->nama_nasabah;
                $receiver_saldo               = $sub_res_receiver->saldo;             
                $receiver_alamat              = $sub_res_receiver->alamat;  
                $RECEIVERSTATUS               = $sub_res_receiver->status;  
                $receiver_errorstatus         = $sub_res_receiver->message;  
                               
            }
            if($RECEIVERSTATUS == FALSE){
                $receiver_errorstatus         = "rek_receiver invalid";
                $this->logAction('check saldo', $traceid, array(),$receiver_errorstatus );
                $this->Res_cek_rek('115', $receiver_errorstatus);
            }
            if($SENDERSTATUS == FALSE){
                $sender_errorstatus         = "rek_sender invalid";
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('116', $sender_errorstatus);
            }
            if($SENDERSTATUS == TRUE){                
                    if(intval($in_nominal) >= $sender_saldo){ // cek saldo
                        $SENDERSTATUS           = FALSE;
                        $sender_errorstatus     = "saldo insuficent balance";
                        $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                        $this->Res_cek_rek('117', $sender_errorstatus);
                    }
            }
        }
        elseif($in_codetransfer == "101"){           
            $datasender     = $this->_Cekrek($in_rek_sender);
            $this->logAction('check saldo', $traceid, array(),$in_codetransfer );
            $this->logAction('check saldo', $traceid, array(),$datasender);
            if($datasender){
                $sub_res_sender = $this->_Jdecode($datasender);
                $sender_no_rekening         = $sub_res_sender->norekening;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->saldo;             
                $sender_alamat              = $sub_res_sender->alamat;  
                $SENDERSTATUS               = $sub_res_sender->status;  
                $sender_errorstatus         = $sub_res_sender->message;  
            }else{
                $sender_errorstatus         = "rek_sender invalid";
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('116', $sender_errorstatus);
            }          
            
            if($this->_Cekbank($in_kode_bank_receiver)){
                $RECEIVERSTATUS             =  TRUE;
                $receiver_errorstatus       =  "";
            }else{
                $receiver_errorstatus         = "codebank invalid";
                $this->logAction('check saldo', $traceid, array(),$receiver_errorstatus );
                $this->Res_cek_rek('118', $receiver_errorstatus);
            }
            $receiver_no_rekening         = $in_rek_receiver;                
            $receiver_nama_nasabah        = $receiver_nama_nasabah;
            $receiver_saldo               = "";             
            $receiver_alamat              = $receiver_alamat;  
            $RECEIVERSTATUS               = $RECEIVERSTATUS;  
            //$receiver_errorstatus         = $message;
            
             if($SENDERSTATUS == TRUE){                
                if(intval($in_nominal + $sender_no_rekening) >= $sender_saldo){ // cek saldo
                    $SENDERSTATUS      = FALSE;
                    $sender_errorstatus     = "saldo insuficent balance"; 
                    $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                    $this->Res_cek_rek('117', $sender_errorstatus);
                }
             }
             $this->logAction('get data', $traceid, array(),'$this->_tarik('.$sender_no_rekening.','.$in_nominal.','.$cost_adm_transfer.','.$in_agent_id.')');
             $res_tarik = $this->_tarik($sender_no_rekening, $in_nominal, $cost_adm_transfer, $in_agent_id,$in_rek_receiver,$in_kode_bank_receiver);
             $this->logAction('cresponse', $traceid, array(), $res_tarik);
             if($this->isJSON($res_tarik)){ 
                $rdecode = $this->_Jdecode($res_tarik);
                $rstatus    =  $rdecode->status;
                $rmasterid   =  $rdecode->masterid;
                $rmessage    =  $rdecode->message;
                               
             }else{
                  $this->Res_cek_rek('116', 'invalid');
             }             
             if($rstatus == FALSE){
                 $this->Res_cek_rek('116', 'invalid');
             }
             
        }
        elseif($in_codetransfer == "102"){
            $datareceiver   = $this->_Cekrek($in_rek_receiver);
            $this->logAction('check saldo', $traceid, array(),$in_codetransfer );
            $this->logAction('check saldo', $traceid, array(),$datareceiver );
            if($datareceiver){
                $sub_res_receiver = $this->_Jdecode($datareceiver);
                $receiver_no_rekening         = $sub_res_receiver->norekening;                
                $receiver_nama_nasabah        = $sub_res_receiver->nama_nasabah;
                $receiver_saldo               = $sub_res_receiver->saldo;             
                $receiver_alamat              = $sub_res_receiver->alamat;  
                $RECEIVERSTATUS               = $sub_res_receiver->status;  
                $receiver_errorstatus         = $sub_res_receiver->message;                                 
            }
            if($RECEIVERSTATUS == FALSE){
                $receiver_errorstatus         = "rek_receiver invalid";
                $this->logAction('check saldo', $traceid, array(),$receiver_errorstatus );
                $this->Res_cek_rek('115', $receiver_errorstatus);
            }
            if($this->_Cekbank($in_kode_bank_sender)){
                $SENDERSTATUS           =  TRUE;
                $sender_errorstatus            =  "";
            }else{                
                $sender_errorstatus            =  "kode_bank_sender";
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('118', $sender_errorstatus);
            }
            if(empty($in_rek_sender)){                
                               
                $sender_errorstatus         = "rek_sender invalid";
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('116', $sender_errorstatus);
            }
            $sender_no_rekening         = $in_rek_sender;                
            $sender_nama_nasabah        = $sender_nama_nasabah;
            $sender_saldo               = "";             
            $sender_alamat              = $sender_alamat;  
            $SENDERSTATUS               = $SENDERSTATUS;  
            $tarif_code                 = $this->_Cret_tarircode();
        }
        else{
            return false;
        }       
               
        if($SENDERSTATUS == TRUE && $RECEIVERSTATUS == TRUE){
            $in_status_session =  '1';
            $status = TRUE;  
            if(empty($arr_message)){
                $arr_message = '';
            }
            $this->logAction('check status', $traceid, array('msg ' => $arr_message), 'Sender & Receiver is complete');                
        }else{
            $arr_message    = 'problem internal';  
            $this->logAction('check status', $traceid, array('msg ' => $arr_message.' Salah'), 'Sender & Receiver not complete');
            $this->Res_cek_rek('120', $arr_message);
        }
        $id_transfer = trim($in_agent_id).$this->_rand_id();
        $gettotal = $in_nominal + $cost_adm_transfer;
        $arr = array(
            'status'                    => TRUE,
            'errorcode'                 => '100',
            'message'                   => trim($arr_message),
            'sender_no_rekening'        => $sender_no_rekening,
            'sender_nama_nasabah'       => $sender_nama_nasabah,
            'sender_kode_bank'          => $in_kode_bank_sender,
            'sender_alamat'             => $sender_alamat,
            'receiver_no_rekening'      => $receiver_no_rekening,
            'receiver_nama_nasabah'     => $receiver_nama_nasabah,
            'receiver_kode_bank'        => $in_kode_bank_receiver,
            'receiver_alamat'           => $receiver_alamat,
            'nominal'                   => $this->Rp($in_nominal),
            'adm'                       => $this->Rp($cost_adm_transfer),
            'total'                     => $this->Rp($gettotal),
            'tarif_code'                => $tarif_code,
            'code'                      => $id_transfer,
        );
        $arr_inst   = array(
            'agent_id'              => $in_agent_id,
            'rekening_sender'       => $in_rek_sender,
            'kode_bank_sender'      => $in_kode_bank_sender,
            'nama_sender'           => $sender_nama_nasabah,
            'alamat_sender'         => $sender_alamat,
            'rekening_receiver'     => $receiver_no_rekening,
            'kode_bank_receiver'    => $in_kode_bank_receiver,
            'nama_receiver'         => $receiver_nama_nasabah,
            'alamat_receiver'       => $receiver_alamat,
            'kode_transfer'         => $in_codetransfer,
            'nominal'               => $in_nominal,
            'cost_adm'              => $cost_adm_transfer,
            'total'                 => $in_nominal + $cost_adm_transfer,
            'status'                => $in_status_session,
            'ip'                    => $this->input->ip_address(),
            'usr_agent'             => $this->_uagent(),
            'code_transfer'         => $id_transfer,
            'master_id_sender'      => $rmasterid,
            'tarif_code'            => $tarif_code,
            'respon_json'           => json_encode($arr, TRUE),
            'mesg_desc_sys'         => 'SENDER:'.$sender_errorstatus.' || RECEIVER:'.$receiver_errorstatus,
        );
        
        $this->Tab_model->Ins_Tbl('transfer_ses_e',$arr_inst);    
        $this->logAction('insert', $traceid,$arr,'Tab_model->Ins_Tbl->transfer_ses_e' );
        $this->logAction('response', $traceid, $arr_inst, 'Success');
        $this->response($arr);      
    }    
    public function BmtKeBankLain_inquiry_post() {
    
        $traceid = $this->logid();
        $this->logheader($traceid);
        $this->_Check_postdata();
        $in_agent_id                = $this->input->post('agentid') ?: '';
        $in_rek_sender              = $this->input->post('rek_sender') ?: '';
        $in_rek_receiver            = $this->input->post('rek_receiver') ?: '';
        $in_codetransfer            = "101";      // TRANSFER ANTAR BANK  DARI BMT KE BANK LAIN         
        $in_nominal                 = $this->input->post('nominal') ?: '';        
        
        //----------********-------
                
        $cost_adm_transfer = 0;
        $masterid   = '';
        if(empty($in_agent_id)){            
            $this->logAction('response', $traceid, array(), 'Failed / data agent is empty');
            $this->Res_cek_rek('104', 'agent invalid');
        }
        if(empty($in_nominal)){            
            $this->logAction('response', $traceid, array(), 'Failed / nominal is empty');
            $this->Res_cek_rek('101', 'nominal isempty');
        }
        if(empty($in_rek_sender)){            
            $this->logAction('response', $traceid, array(), 'Failed / rek sender is empty');
            $this->Res_cek_rek('102', 'rek sender isempty');
        }
        if(empty($in_rek_receiver)){            
            $this->logAction('response', $traceid, array(), 'Failed / rek receiver is empty');
            $this->Res_cek_rek('103', 'rek receiver invalid');
        }
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / data agent not found in databases');
            $this->Res_cek_rek('104', 'agent invalid');
        }
        
        
        $res_kode_bank_transfer     = $this->Trf_model->Cek_bank_default();
        $in_kode_bank_sender        = $res_kode_bank_transfer;
//        $receiver_nama_nasabah      = $this->input->post('nama_nasabah_receiver');
//        $receiver_alamat            = $this->input->post('alamat_receiver');
        $in_kode_bank_receiver      = $this->input->post('kode_bank_receiver');
        
        if(empty($in_kode_bank_receiver)){
            $this->logAction('response', $traceid, array(), 'Failed / data code bank not found in databases');
            $this->Res_cek_rek('105', 'kodebank invalid');
        }
        
        if($this->_Cekbank($in_kode_bank_receiver) == false){
                $this->logAction('response', $traceid, array(), 'Failed / data code bank not found in databases');
                $this->Res_cek_rek('105', 'kodebank invalid');
        }
        
        $res_call_jenis_trf = $this->Trf_model->Model_transfer($in_codetransfer);
        $this->logAction('select', $traceid, $res_call_jenis_trf, 'Trf_model->Model_transfer('.$in_codetransfer.')');
        if($res_call_jenis_trf){
            foreach ($res_call_jenis_trf as $sub_jenis_trf) {                
                $set_adm_default    = $sub_jenis_trf->set_adm_default;
                $adm_default        = $sub_jenis_trf->adm_default;
                $set_limit          = $sub_jenis_trf->set_limit;
                $min_trf            = $sub_jenis_trf->min_trf;
                $max_trf            = $sub_jenis_trf->max_trf;
                $cost_adm_bmt       = $sub_jenis_trf->adm_bmt;
            }
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not in database');
            $this->Res_cek_rek('106', 'codetransfer invalid');
        }
        if(empty($cost_adm_bmt)){
            $cost_adm_bmt   = transfer_adm_bmt_default;
        }
            
        if(strtolower($set_adm_default) == "bydefault"){
            $cost_adm_transfer = $adm_default;
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by default');
            
        }elseif(strtolower($set_adm_default) == "byreceiverbank")            {
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_receiver);
            if($res_cek_bank){
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }            
            }
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by byreceiverbank');
        }elseif(strtolower($set_adm_default) == "bysenderbank"){
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_sender);
            if($res_cek_bank){
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }            
            }
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by bysenderbank');
        }
        
        $this->logAction('result', $traceid, array(), 'batas min :'.$min_trf);
        $this->logAction('result', $traceid, array(), 'batas max :'.$max_trf);
        if(strtolower($set_limit) == "yes"){ // cek setting limit
            if($in_nominal < $min_trf){ // limit nominal transfer
                $this->logAction('result', $traceid, array(), 'The nominal limit to be transferred exceeds the limit :nominal :'.$in_nominal);              
                $this->Res_cek_rek('111', 'nominal min transfer '.$min_trf);
            }
            if($in_nominal > $max_trf){ // limit nominal transfer
                $this->logAction('result', $traceid, array(), 'The nominal limit to be transferred exceeds the limit :nominal :'.$in_nominal);
                $this->Res_cek_rek('112', 'nominal max transfer '.$max_trf);
            }
        }       
            
        //$arr = array();
        $in_status_session  = 1;
        $rmasterid          = "";
                   
            $datasender     = $this->_Cekrek($in_rek_sender);
            $this->logAction('check saldo', $traceid, array(),$in_codetransfer);
            $this->logAction('check saldo', $traceid, array(),$datasender);
            if($datasender){
                $sub_res_sender = $this->_Jdecode($datasender);
                $sender_no_rekening         = $sub_res_sender->norekening;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->saldo;             
                $sender_alamat              = $sub_res_sender->alamat;  
                $SENDERSTATUS               = $sub_res_sender->status;  
                $sender_errorstatus         = $sub_res_sender->message;  
            }else{
                $sender_errorstatus         = "rek_sender invalid";
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('116', $sender_errorstatus);
            }         
            
            if(intval($in_nominal + $adm_default) >= $sender_saldo){ // cek saldo
                $SENDERSTATUS      = FALSE;
                $sender_errorstatus     = "saldo insuficent balance"; 
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('117', $sender_errorstatus);
            }
            
            
            //inquiry data receiver 
            //request ke AJ
            $arr_post = array(
                'acc_no'            => '0014400143',
                'acc_name'          => '004/'.substr($sender_nama_nasabah,0,20),
                'dest_bank_code'    => $in_kode_bank_receiver,
                'dest_acc_no'       => $in_rek_receiver,
                'amount'            => $in_nominal,
                'terminal_id'       => '100001',
                'area_code'         => '192',
                'chaneltype'        => '6010',
                'refcust_number'    => '1000000'.date('Hs'),
                'regencycode'       => 'JKT'
            );
            $this->logAction('inquiry', $traceid, array(),'parameter url : '.urlinq );
            $this->logAction('inquiry', $traceid, $arr_post,'parameter req' );
            $req_inq_aj = $this->HitPostAJ(urlinq,$arr_post);
            //$req_inq_aj = '{"status":"00","message":"OK","acc_no":"0001130014400143","acc_name":"AMT\/999\/Lilis Nurul husna","dest_bank_code":"014","dest_acc_no":"4780120652","dest_acc_name":"EDI SUPRIYANTO","amount":"10000","terminal_id":"100001","transaction_id":"21811276088"}';
            //$this->logAction('inquiry', $traceid, array(),'response req : '.$req_inq_aj );
            if($req_inq_aj){
                $viq = json_decode($req_inq_aj,true);                                    
                $receiver_status                = $viq['status'] ?: '';
                $receiver_message               = $viq['message'] ?: '';                  
                                
            }else{
                $receiver_errorstatus         = "invalid artajasa";
                $this->logAction('check saldo', $traceid, array(),$receiver_errorstatus );
                $this->Res_cek_rek('118', $receiver_errorstatus);
            }    
            
            if($receiver_status == "00"){  
                 $viq = json_decode($req_inq_aj,true);
                $receiver_no_rekening           = $viq['dest_acc_no'] ?: '';  
                $receiver_nama_nasabah          = $viq['dest_acc_name'] ?: '';
                $receiver_amount                = $viq['amount'] ?: '';
                $receiver_trxid                 = $viq['transaction_id'] ?: '';
                $receiver_acc_no                = $viq['acc_no'] ?: '';
                $receiver_accname               = $viq['acc_name'] ?: '';
                $receiver_destbankcode          = $viq['dest_bank_code'] ?: '';
                $receiver_terminalid            = $viq['terminal_id'] ?: '';     
            }else{
                switch ($receiver_status) {
                    case 16:
                        $receiver_errorstatus   = "AJ-General Error. ".$receiver_message;
                        $this->logAction('response', $traceid, array(),$receiver_errorstatus );
                        $this->Res_cek_rek('119', $receiver_errorstatus);
                    break;
                    case 17:
                        $receiver_errorstatus   = "rek receiver isempty";
                        $this->logAction('response', $traceid, array(),$receiver_message );
                        $this->Res_cek_rek('103', $receiver_message);
                    break;
                    case 19:
                        $receiver_errorstatus   = "AJ Link Down";
                        $this->logAction('response', $traceid, array(),$receiver_message );
                        $this->Res_cek_rek('120', $receiver_message);
                    break;
                    default:
                        $receiver_errorstatus   = "gateway error";
                        $this->logAction('response', $traceid, array(),$receiver_errorstatus );
                        $this->Res_cek_rek('120', $receiver_errorstatus);
                    break;
                }
            }
        $datareceiver = $this->_Cekbank_name($in_kode_bank_receiver);
        if($datareceiver){
            foreach ($datareceiver as $vb) {
                $this->namabank_receiver   = $vb->nama_bank;
            }
        }
        
        $data_sender = $this->_Cekbank_name($in_kode_bank_sender);
        if($data_sender){
            foreach ($data_sender as $vb) {
                $this->namabank_sender   = $vb->nama_bank;
            }
        }
        
        $id_transfer = trim($in_agent_id).$this->_rand_id();
        $gettotal = $in_nominal + $cost_adm_transfer + $cost_adm_bmt;
        $gettotaladmin = $cost_adm_transfer + $cost_adm_bmt;
        $arr = array(
            'status'                    => TRUE,
            'errorcode'                 => '100',
            'message'                   => 'OK',
            'sender_no_rekening'        => $sender_no_rekening,
            'sender_nama_nasabah'       => $sender_nama_nasabah,
            'sender_kode_bank'          => strtoupper($this->namabank_sender),
            'receiver_no_rekening'      => $receiver_no_rekening,
            'receiver_nama_nasabah'     => $receiver_nama_nasabah,
            'receiver_kode_bank'        => $in_kode_bank_receiver.'/'.strtoupper($this->namabank_receiver),
            'nominal'                   => $this->Rp($in_nominal),
            'adm'                       => $this->Rp($gettotaladmin),
            'total'                     => $this->Rp($gettotal),            
            'code'                      => $id_transfer,
        );
        $arr_inst   = array(
            'agent_id'              => $in_agent_id,
            'rekening_sender'       => $in_rek_sender,
            'kode_bank_sender'      => $in_kode_bank_sender,
            'nama_sender'           => $sender_nama_nasabah,
            'alamat_sender'         => $sender_alamat,
            'rekening_receiver'     => $receiver_no_rekening,
            'kode_bank_receiver'    => $in_kode_bank_receiver,
            'nama_receiver'         => $receiver_nama_nasabah,
            'kode_transfer'         => $in_codetransfer,
            'nominal'               => $in_nominal,
            'cost_adm'              => $cost_adm_transfer,
            'total'                 => $gettotal,
            'status'                => $in_status_session,
            'ip'                    => $this->input->ip_address(),
            'usr_agent'             => $this->_uagent(),
            'code_transfer'         => $id_transfer,
            'master_id_sender'      => $rmasterid,
            'tarif_code'            => '',
            'proses_type'           => 'inquiry',
            'respon_json'           => json_encode($arr, TRUE),
            'mesg_desc_sys'         => 'OK',
            'd_response'            => $req_inq_aj,
            'd_acc_no'              => $receiver_acc_no,
            'd_acc_name'            => $receiver_accname,
            'd_transaction_id'      => $receiver_trxid,
            'cost_adm_bmt'          => $cost_adm_bmt
        );
        
        $this->Tab_model->Ins_Tbl('transfer_ses_e',$arr_inst);    
        $this->logAction('insert', $traceid,$arr,'Tab_model->Ins_Tbl->transfer_ses_e' );
        $this->logAction('response', $traceid, $arr_inst, 'Success');
        $this->response($arr);      
    }       
    public function Check_rek_offline($traceid = '',$in_agent_id = '',$in_rek_sender = '',$in_rek_receiver = '',$in_codetransfer = '100',$in_nominal ='0') {
        
        $cost_adm_transfer = 0;
        $masterid   = '';
        if(empty($in_agent_id)){            
            $this->logAction('response', $traceid, array(), 'Failed / data agent is empty');
            $this->Res_cek_rek('104', 'agent invalid');
        }
        if(empty($in_nominal)){            
            $this->logAction('response', $traceid, array(), 'Failed / nominal is empty');
            $this->Res_cek_rek('101', 'nominal isempty');
        }
        if(empty($in_rek_sender)){            
            $this->logAction('response', $traceid, array(), 'Failed / rek sender is empty');
            $this->Res_cek_rek('102', 'rek sender isempty');
        }
        if(empty($in_rek_receiver)){            
            $this->logAction('response', $traceid, array(), 'Failed / rek receiver is empty');
            $this->Res_cek_rek('103', 'rek receiver isempty');
        }
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / data agent not found in databases');
            $this->Res_cek_rek('105', 'agent invalid');
        }

        if($in_codetransfer == "100"){ //local antar rek bmt
            $res_kode_bank_transfer     = $this->Trf_model->Cek_bank_default();
            $in_kode_bank_sender        = $res_kode_bank_transfer;
            $in_kode_bank_receiver      = $res_kode_bank_transfer;
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not recognized');
            $this->Res_cek_rek('106', 'codetransfer invalid');
        }
        $res_call_jenis_trf = $this->Trf_model->Model_transfer($in_codetransfer);
        $this->logAction('select', $traceid, $res_call_jenis_trf, 'Trf_model->Model_transfer('.$in_codetransfer.')');
        if($res_call_jenis_trf){
            foreach ($res_call_jenis_trf as $sub_jenis_trf) {                
                $set_adm_default    = $sub_jenis_trf->set_adm_default;
                $adm_default        = $sub_jenis_trf->adm_default;
                $set_limit          = $sub_jenis_trf->set_limit;
                $min_trf            = $sub_jenis_trf->min_trf;
                $max_trf            = $sub_jenis_trf->max_trf;
            }
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not in database');
            $this->Res_cek_rek('106', 'codetransfer invalid');
        }
        if(strtolower($set_adm_default) == "bydefault"){
            $cost_adm_transfer = $adm_default;
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by default');
            
        }elseif(strtolower($set_adm_default) == "byreceiverbank"){
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_receiver);
            if($res_cek_bank){
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }            
            }
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by byreceiverbank');
        }elseif(strtolower($set_adm_default) == "bysenderbank"){
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_sender);
            if($res_cek_bank){
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }            
            }
            $this->logAction('check cost adm', $traceid, array('biaya adm' => $cost_adm_transfer), 'set by bysenderbank');
        }
        $this->logAction('result', $traceid, array(), 'batas min :'.$min_trf);
        $this->logAction('result', $traceid, array(), 'batas max :'.$max_trf);
        if(strtolower($set_limit) == "yes"){ // cek setting limit
            if($in_nominal < $min_trf){ // limit nominal transfer
                $this->logAction('result', $traceid, array(), 'The nominal limit to be transferred exceeds the limit :nominal :'.$in_nominal);              
                $this->Res_cek_rek('111', 'nominal min transfer '.$min_trf);
            }
            if($in_nominal > $max_trf){ // limit nominal transfer
                $this->logAction('result', $traceid, array(), 'The nominal limit to be transferred exceeds the limit :nominal :'.$in_nominal);
                $this->Res_cek_rek('112', 'nominal max transfer '.$max_trf);
            }
        }
            
        //$arr = array();
        $tarif_code         = "";
        $in_status_session  = 0;
        $rmasterid          = "";
        if($in_codetransfer == "100"){
            $datasender     = $this->_Cekrek($in_rek_sender);
            if($datasender){
                $sub_res_sender = $this->_Jdecode($datasender);
                $sender_no_rekening         = $sub_res_sender->norekening;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->saldo;             
                $sender_alamat              = $sub_res_sender->alamat;  
                $SENDERSTATUS               = $sub_res_sender->status;  
                $sender_errorstatus                = $sub_res_sender->message;
            }
            $datareceiver   = $this->_Cekrek($in_rek_receiver);
            if($datareceiver){
                $sub_res_receiver = $this->_Jdecode($datareceiver);
                $receiver_no_rekening         = $sub_res_receiver->norekening;                
                $receiver_nama_nasabah        = $sub_res_receiver->nama_nasabah;
                $receiver_saldo               = $sub_res_receiver->saldo;             
                $receiver_alamat              = $sub_res_receiver->alamat;  
                $RECEIVERSTATUS               = $sub_res_receiver->status;  
                $receiver_errorstatus         = $sub_res_receiver->message;  
                               
            }
            if($RECEIVERSTATUS == FALSE){
                $receiver_errorstatus         = "rek_receiver invalid";
                $this->logAction('check saldo', $traceid, array(),$receiver_errorstatus );
                $this->Res_cek_rek('115', $receiver_errorstatus);
            }
            if($SENDERSTATUS == FALSE){
                $sender_errorstatus         = "rek_sender invalid";
                $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                $this->Res_cek_rek('116', $sender_errorstatus);
            }
            if($SENDERSTATUS == TRUE){                
                    if(intval($in_nominal) >= $sender_saldo){ // cek saldo
                        $SENDERSTATUS           = FALSE;
                        $sender_errorstatus     = "saldo insuficent balance";
                        $this->logAction('check saldo', $traceid, array(),$sender_errorstatus );
                        $this->Res_cek_rek('117', $sender_errorstatus);
                    }
            }
        }else{
            return false;
        }       
               
        if($SENDERSTATUS == TRUE && $RECEIVERSTATUS == TRUE){
            $in_status_session =  '1';
            $status = TRUE;  
            if(empty($arr_message)){
                $arr_message = '';
            }
            $this->logAction('check status', $traceid, array('msg ' => $arr_message), 'Sender & Receiver is complete');                
        }else{
            $arr_message    = 'problem internal';  
            $this->logAction('check status', $traceid, array('msg ' => $arr_message.' Salah'), 'Sender & Receiver not complete');
            $this->Res_cek_rek('120', $arr_message);
        }
        $id_transfer = trim($in_agent_id).$this->_rand_id();
        $gettotal = $in_nominal + $cost_adm_transfer;
        $arr = array(
            'errorcode'                 => '100',
            'message'                   => trim($arr_message),
            'sender_no_rekening'        => $sender_no_rekening,
            'sender_nama_nasabah'       => $sender_nama_nasabah,
            'sender_kode_bank'          => $in_kode_bank_sender,
            'sender_alamat'             => $sender_alamat,
            'receiver_no_rekening'      => $receiver_no_rekening,
            'receiver_nama_nasabah'     => $receiver_nama_nasabah,
            'receiver_kode_bank'        => $in_kode_bank_receiver,
            'receiver_alamat'           => $receiver_alamat,
            'nominal'                   => $in_nominal,
            'adm'                       => $cost_adm_transfer,
            'total'                     => $gettotal,
            'code'                      => $id_transfer,
        );
        $arr_inst   = array(
            'agent_id'              => $in_agent_id,
            'rekening_sender'       => $in_rek_sender,
            'kode_bank_sender'      => $in_kode_bank_sender,
            'nama_sender'           => $sender_nama_nasabah,
            'alamat_sender'         => $sender_alamat,
            'rekening_receiver'     => $receiver_no_rekening,
            'kode_bank_receiver'    => $in_kode_bank_receiver,
            'nama_receiver'         => $receiver_nama_nasabah,
            'alamat_receiver'       => $receiver_alamat,
            'kode_transfer'         => $in_codetransfer,
            'nominal'               => $in_nominal,
            'cost_adm'              => $cost_adm_transfer,
            'total'                 => $in_nominal + $cost_adm_transfer,
            'status'                => $in_status_session,
            'ip'                    => $this->input->ip_address(),
            'usr_agent'             => $this->_uagent(),
            'code_transfer'         => $id_transfer,
            'master_id_sender'      => $rmasterid,
            'tarif_code'            => $tarif_code,
            'respon_json'           => json_encode($arr, TRUE),
            'mesg_desc_sys'         => 'SENDER:'.$sender_errorstatus.' || RECEIVER:'.$receiver_errorstatus,
        );
        
        $this->Tab_model->Ins_Tbl('transfer_ses_e',$arr_inst);    
        $this->logAction('insert', $traceid,$arr,'Tab_model->Ins_Tbl->transfer_ses_e' );
        //$this->logAction('response', $traceid, , 'Success');
        //$this->response($arr);  
        
        return json_encode($arr);
    }    
    public function Riwayat_post() {
        $traceid = $this->logid();
        $this->logheader($traceid);
        $this->_Check_postdata();
        $in_agent_id                = $this->input->post('agentid') ?: '';
        $in_waktu                   = $this->input->post('dtm') ?: '';
        $in_codetransfer            = $this->input->post('codetransfer') ?: '';     
        if(empty($in_agent_id)){
            $this->logAction('response', $traceid, array(), 'Failed / data agent is empty');
            $this->Res_riwayat('110', 'agent isempty');
        }
        if(empty($in_waktu)){
            $this->logAction('response', $traceid, array(), 'Failed / data dtm is empty');
            $this->Res_riwayat('111', 'dtm isempty');
        }
        if(is_numeric($in_codetransfer) && strlen($in_codetransfer) == 3 ){
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / data codetransfer is empty');
            $this->Res_riwayat('113', 'codetransfer invalid');
        }
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / data agent not found in databases');
            $this->Res_riwayat('112', 'agent invalid');
        }        
        
        //$this->formatdtm = date_format($in_waktu,"Y-m-d");
        $this->formatdtm = date("Y-m-d", strtotime($in_waktu));
        $this->logAction('response', $traceid, array(), 'date format : '.$this->formatdtm);
        if($in_codetransfer == "100"){ //local antar rek bmt            
        }elseif($in_codetransfer == "101"){ // bmt ke bank lain                   
        }elseif($in_codetransfer == "102"){ // bank lain ke bmt            
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not recognized');
            $this->Res_riwayat('113', 'codetransfer invalid');
        }        
       
        //$res_listperday = $this->Trf_model->List_perday($in_codetransfer,  $this->formatdtm);
        $res_listperday = false;
        $this->logAction('select', $traceid, array(), 'Trf_model->List_perday('.$in_codetransfer.','.  $this->formatdtm.')');
        if($res_listperday){
            $data = array();
            $arr = array();
            foreach ($res_listperday as $val) {
                $data['bank_sender']    = $val->bank_pengirim;
                $data['rek_sender']     = $val->rekening_sender;
                $data['nama_sender']    = $val->nama_sender;
                $data['bank_receiver']  = $val->bank_penerima;
                $data['rek_receiver']   = $val->rekening_receiver;
                $data['nama_receiver']  = $val->nama_receiver;
                $data['nominal']        = $this->Rp($val->nominal);
                if($val->status == 1){
                    $data['status'] = 'ANTRIAN';
                    $data['codesave'] = '';
                }elseif($val->status == 2){
                    $data['status'] = 'BERHASIL'; 
                    $data['codesave'] = $val->code_transfer;
                }elseif($val->status == 3){
                    $data['status'] = 'GAGAL';  
                    $data['codesave'] = '';
                }elseif($val->status == 4){
                    $data['status'] = 'PROSES';
                    $data['codesave'] = '';
                }else{
                    $data['status'] = '-';
                    $data['codesave'] = '';
                }                
                $arr[] = $data;
            }
            $arr_res = array(
                'status'                    => FALSE,
                'errorcode'                 => '100',
                'message'                   => 'success',
                'list'                      => $arr);
            $this->response($arr_res);
        }else{
            $this->logAction('response', $traceid, array(), 'Failed / codetransfer not recognized');
            $this->Res_riwayat('101', 'data isempty');
            
        }        
    }
    function _Cret_tarircode() {
        $res_code = 0;
        do{
            $tarif_code                  = random_string('nozero', 3);
            $cek_res_code                = $this->Trf_model->Tarifcode($tarif_code);
            if($cek_res_code){ $res_code = 0;  }
            else{$res_code               = 1;  }
        }
        while ($res_code == 0);
        return $tarif_code;   
        
    }
    public function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
     }    
    function _Cekbank($inkodebank) {
        $result_data = $this->Trf_model->Cekbank($inkodebank);
        if($result_data){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    function _Cekbank_name($inkodebank) {
        $result_data = $this->Trf_model->Cekbank($inkodebank);
        return $result_data;
    }
    function _Jdecode($data) {
        return json_decode($data);
    }
    function _Cekrek($in_norekening) {
        $result_data = $this->Tab_model->Tab_pro_nas_join($in_norekening);
        if($result_data){
                foreach($result_data as $sub_res_sender){
                    $sender_nasabah_id         = $sub_res_sender->nasabah_id;                
                    $sender_no_rekening         = $sub_res_sender->NO_REKENING;                
                    $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                    $sender_saldo               = $sub_res_sender->SALDO_AKHIR;             
                    $sender_alamat              = $sub_res_sender->alamat;             
                }           
                $sender_errorstatus     = "";
                $SENDERSTATUS           = TRUE;

        }
        else{
            $SENDERSTATUS           = FALSE;
            $sender_errorstatus     = "no_rekening";
            $sender_no_rekening     = "";
            $sender_nama_nasabah    = "";
            $sender_alamat          = "";
            $sender_saldo           = 0;

        }
        $data = array(
            "norekening"    => $sender_no_rekening,
            "nama_nasabah"  => $sender_nama_nasabah,
            "alamat"        => $sender_alamat,
            "saldo"         => $sender_saldo,
            "status"        => $SENDERSTATUS,
            "message"       => $sender_errorstatus,
        );
//            return $this->response($data); 
        

        return json_encode($data);
    }
    protected function _Check_postdata() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $data = array('status' => FALSE,'error' => 'Request method failed');
            $this->response($data);
            return;
        }
        
    }
    function _uagent(){
        $this->load->library('user_agent');
        if ($agent = $this->agent->agent_string())
        {
                $agent = $this->agent->agent_string();
        }
        elseif ($this->agent->is_robot())
        {
                $agent = $this->agent->robot();
        }
        elseif ($this->agent->is_mobile())
        {
                $agent = $this->agent->mobile();
        }
        elseif ($this->agent->is_browser())
        {
                $agent = $this->agent->browser().' '.$this->agent->version();
        }
        else
        {
                $agent = 'Unidentified User Agent';                               
        }
        return $agent;
    }
    function _rand_id() {       
        $in_random = random_string('alnum', 20); 
        if(empty($in_random)){
            $in_random = random_string('alnum', 20);
        }
        return date('ymdHi').$in_random;
    }
    public function BmtKeBankLain_transfer_post() {
        $trace_id       = $this->logid();
        $this->logheader($trace_id);
	$in_agentid     = $this->input->post('agentid');
        $in_code        = $this->input->post('code');
        //***************************
        
        if(strlen($in_code) < 10 || strlen($in_code) > 40){
            $data = array('status' => FALSE,'errorcode'=>'112','message' => 'invalid code');
            $data = array_merge($data, $this->arrtrf);
            $this->logAction('response',$trace_id,$data,'failed code transfer');
            $this->response($data);
        }
        if(empty($in_code)){
            $data = array('status' => FALSE,'errorcode'=>'112','message' => 'invalid code');
            $data = array_merge($data, $this->arrtrf);
            $this->logAction('response',$trace_id,$data,'failed code transfer');
            $this->response($data);
        }
        if(empty($in_agentid)){
            $data = array('status' => FALSE,'errorcode'=>'111','message' => 'isempty agentid');
            $data = array_merge($data, $this->arrtrf);
            $this->logAction('response',$trace_id,$data,'failed,error data agent is empty');
            $this->response($data);
        }        
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array('status' => FALSE,'errorcode'=>'114','message' => 'invalid agentid');
            $this->logAction('response',$trace_id,$data,'failed, agent not found in databases ');
            $this->response($data);
        }
        
        $res_call_tiad_trf = $this->Tab_model->Tiad_trf_ses($in_agentid,$in_code);
        $this->logAction('get tiad session',$trace_id,array(),'Tab_model->Tiad_trf_ses('.$in_agentid.','.$in_code.')');
        if(!$res_call_tiad_trf){
            // aku rubah yang ini pri  echo  "4|expired";
            $data = array('status' => FALSE,'errorcode'=>'115','message' => 'invalid code');
            $data = array_merge($data, $this->arrtrf);
            $this->logAction('response',$trace_id,$data,'code transfer not found in databases');
            $this->response($data);
        }
	$this->logAction('result',$trace_id,$res_call_tiad_trf,'get data tiad transfer');	
        foreach($res_call_tiad_trf as $sub_call_tiad_trf){
            $gtransfer_id           = $sub_call_tiad_trf->id_transfer;
            $gsender_rekening       = $sub_call_tiad_trf->rekening_sender;
            $greceiver_rekening     = $sub_call_tiad_trf->rekening_receiver;
            $gsender_nominal        = $sub_call_tiad_trf->nominal;
            $gsender_cost_adm       = $sub_call_tiad_trf->cost_adm ?: 0;
            $gsender_cost_adm_bmt   = $sub_call_tiad_trf->cost_adm_bmt ?: 3000;
            $gsender_total          = $sub_call_tiad_trf->total;
            $ggen_code              = $sub_call_tiad_trf->code_transfer;
            $greceiver_name         = $sub_call_tiad_trf->nama_receiver;
            $gsender_name           = $sub_call_tiad_trf->nama_sender;
            $greceiver_kode_bank    = $sub_call_tiad_trf->kode_bank_receiver;
            $gsender_kode_bank      = $sub_call_tiad_trf->kode_bank_sender;
            $ggen_code              = $sub_call_tiad_trf->code_transfer;
            $d_transaction_id       = $sub_call_tiad_trf->d_transaction_id;
        }
        $result_data_sender = $this->Tab_model->Tab_pro_nas_join($gsender_rekening);
        $this->logAction('get data',$trace_id,array(),'rek:sender -- Tab_model->Tab_pro_nas_join('.$gsender_rekening.')');
        if($result_data_sender){
            foreach($result_data_sender as $sub_res_sender){
                $sender_no_rekening         = $sub_res_sender->NO_REKENING;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->SALDO_AKHIR;             
                $sender_alamat              = $sub_res_sender->alamat;             
            }    
            $this->logAction('result',$trace_id,array(),'rek sender : '.$sender_no_rekening.'//'.$sender_nama_nasabah.'//'.$sender_saldo.'//'.$sender_alamat);      
        }
        else{
            
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');
            $data = array_merge($data, $this->arrtrf);
            $this->logAction('response',$trace_id,$data,'rekening sender '.$gsender_rekening.' not found in databases');
            $this->response($data);      
        }

        
        if(abs($sender_saldo) > abs($gsender_total)){
            $this->logAction('result',$trace_id,array(),'cek saldo : sender saldo rekening: '.abs($sender_saldo).' Nominal transfer :'.abs($gsender_total));
            $this->logAction('result',$trace_id,array(),'cek saldo : result OK');
        }else{
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');     
            $data = array_merge($data, $this->arrtrf);
            $this->logAction('response',$trace_id,$data,'Insufficent balace, saldo:'.abs($sender_saldo).' Nominal transfer:'.abs($gsender_nominal));
            $this->response($data);
        }
        
        //begin
        $arr_post = array(
                'acc_no'            => '0014400143',
                'acc_name'          => '004/'.substr($sender_nama_nasabah,0,20),
                'dest_bank_code'    => $greceiver_kode_bank,
                'dest_acc_no'       => $greceiver_rekening,
                'dest_acc_name'     => $greceiver_name,
                'amount'            => $gsender_nominal,
                'terminal_id'       => '100001',                
                'transactionid'     => $d_transaction_id
            );
        $this->logAction('transfer', $trace_id, $arr_post,'parameter req');
        $req_trf_aj = $this->HitPostAJ(urltrf, $arr_post);
        //$req_trf_aj = '{"status":"00","message":"OK","acc_no":"0001130014400143","acc_name":"AMT\/999\/SAPTADI NURFARID QQ ","dest_bank_code":"008","dest_acc_no":"1030094542103","dest_acc_name":"SAPTADI NURFARID","amount":"10000.00","terminal_id":"100001","transaction_id":"21812077025","transaction_stan":"003790","transaction_datetime":"20181207074118"}';
        $this->logAction('transfer', $trace_id, $arr_post,'parameter res :'.$req_trf_aj);
        if($req_trf_aj){
                $viq = json_decode($req_trf_aj,true);                                    
                $receiver_no_rekening           = $viq['dest_acc_no'] ?: '';  
                $receiver_nama_nasabah          = $viq['dest_acc_name'] ?: '';
                $receiver_amount                = $viq['amount'] ?: '';
                $receiver_trxid                 = $viq['transaction_id'] ?: '';
                $receiver_status                = $viq['status'] ?: '';
                $receiver_message               = $viq['message'] ?: '';
                $receiver_acc_no                = $viq['acc_no'] ?: '';
                $receiver_accname               = $viq['acc_name'] ?: '';
                $receiver_destbankcode          = $viq['dest_bank_code'] ?: '';
                $receiver_terminalid            = $viq['terminal_id'] ?: '';                      
                $receiver_trans_stan            = $viq['transaction_stan'] ?: '';                      
                $receiver_trans_datetime        = $viq['transaction_datetime'] ?: '';                                    
        }else{
            $receiver_errorstatus         = "invalid artajasa";
            $this->logAction('check saldo', $traceid, array(),$receiver_errorstatus );
            $this->Res_cek_rek('118', $receiver_errorstatus);
        }  
        
        if($receiver_status == "00" || $receiver_status== "20"){                
        }else{
            switch ($receiver_status) {
                case 14:                
                    $data = array('status' => FALSE,'errorcode'=>'119','message' => $receiver_message);
                    $data = array_merge($data, $this->arrtrf);
                    $this->logAction('response',$trace_id,$data,'status failed');
                    $this->response($data); 
                break;
  
                case 16:
                    $data = array('status' => FALSE,'errorcode'=>'120','message' => 'AJ-General Error.'.$receiver_errorstatus);
                    $data = array_merge($data, $this->arrtrf);
                    $this->logAction('response',$trace_id,$data,'status failed ');
                    $this->response($data);
                break;
                case 19:
                    $data = array('status' => FALSE,'errorcode'=>'121','message' => 'AJ Link Down.');
                    $data = array_merge($data, $this->arrtrf);
                    $this->logAction('response',$trace_id,$data,'status failed ');
                    $this->response($data);
                break;
                default:
                    $data = array('status' => FALSE,'errorcode'=>'118','message' => $receiver_errorstatus);
                    $data = array_merge($data, $this->arrtrf);
                    $this->logAction('response',$trace_id,$data,'status respon not initial, please cek your document dev');
                    $this->response($data);
                break;
            }
        }
        
        //end
        //hitung jumlah saldo pengirim
        $sender_sisa_saldo = abs($sender_saldo) - abs($gsender_total);
        //$receiver_jumlah_saldo = abs($receiver_saldo) + abs($gsender_nominal);
        
        $this->LogAction('info', $trace_id, array(), ' sender : saldo awal : '.abs($sender_saldo).' - '.abs($gsender_total).'='.$sender_sisa_saldo);
        //$this->LogAction('info', $trace_id, array(), ' receiver : saldo awal : '.abs($receiver_saldo).' - '.abs($gsender_nominal).'='.$receiver_jumlah_saldo);
        //insert_tab_trans sender
        
        
        $datas = $this->_tarik($sender_no_rekening, $gsender_nominal , 0, $in_agentid, $greceiver_rekening,$greceiver_kode_bank,$trace_id,'dsb');
        $this->logAction('_tarik', $trace_id,array(), '_tarik('.$sender_no_rekening.','.$gsender_nominal .','. '0'.','.$in_agentid.', '.$greceiver_rekening.','.$greceiver_kode_bank.','.$trace_id.',dsb)');
        $this->LogAction('_tarik', $trace_id, array(), 'resp '.$datas);
        
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
        
        $datasr = $this->_tarik_dsb($sender_no_rekening,$gsender_cost_adm, $gsender_cost_adm_bmt, $in_agentid, $greceiver_rekening,$greceiver_kode_bank,$trace_id);
        $this->logAction('_tarik_dsb', $trace_id,array(), '_tarik('.$sender_no_rekening.','.$gsender_cost_adm .','. $gsender_cost_adm_bmt.','.$in_agentid.', '.$greceiver_rekening.','.$greceiver_kode_bank.','.$trace_id.')');
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
        
        $arr_insert = array(
            'user_id'           => $in_agentid, 
            'dtm'               => date('Y-m-d H:i:s'), 
            'kode_transfer'     => '101', 
            'rekening_sender'   => $gsender_rekening, 
            'kode_bank_sender'  => $gsender_kode_bank, 
            'nama_sender'       => $gsender_name, 
            'alamat_sender'     => '', 
            'rekening_receiver' => $greceiver_rekening, 
            'kode_bank_receiver'=> $greceiver_kode_bank, 
            'nama_receiver'     => $greceiver_name, 
            'nominal'           => $gsender_nominal, 
            'cost_adm'          => $gsender_cost_adm, 
            'total'             => $gsender_total, 
            'master_id_sender'  => $masterid_sender, 
            'master_id_receiver'=> $masterid_receiver, 
            'ip'                => $this->input->ip_address(), 
            'last_upd'          => date('Y-m-d H:i:s'), 
            'proses_type'       => 'payment', 
            'd_response'        => $req_trf_aj, 
            'd_acc_no'          => $receiver_acc_no, 
            'd_acc_name'        => $receiver_accname, 
            'd_transaction_id'  => $d_transaction_id, 
            'cost_adm_bmt'      => $gsender_cost_adm_bmt, 
            'trans_stan'        => $receiver_trans_stan, 
            'trans_datetime'    => $receiver_trans_datetime, 
            'chaneltype'        => 'api', 
            'userstype'         => 'agent'
        );  
        
        $getinsert = $this->Tab_model->Ins_Tbl('transfer_log_bmt_kebanklain',$arr_insert);
        $this->LogAction('insert', $trace_id, $arr_insert, 'Tab_model->Ins_Tbl(transfer_log_bmt_kebanklain) : res:'.$getinsert);
        $this->Tab_model->Trf_ses_upd($in_code);        
        $data = array('status' => TRUE,'errorcode'=>'100','message' => 'Succesfully','tstan' => $receiver_trans_stan,'tdtm' => $receiver_trans_datetime);
        $this->logAction('response', $trace_id,$data, 'Transaction is Done');
        $this->response($data);           
    }   
    public function BmtKeBankLain_riwayat_post() {
        $traceid = $this->logid();
        $this->logheader($traceid);
        $in_agent_id                = $this->input->post('agentid') ?: '';
         
        if(empty($in_agent_id)){
            $arr_res = array(
                'status'                    => FALSE,
                'errorcode'                 => '102',
                'message'                   => 'agentid invalid',
                'list'                      => array($this->res_trf_disburment_riwayat));
            $this->logAction('response', $traceid, $arr_res, 'Failed / data agent is empty');
            $this->response($arr_res);
        }                
        if($this->Admin_user2_model->User_by_id($in_agent_id)){            
        }else{
            $arr_res = array(
                'status'                    => FALSE,
                'errorcode'                 => '102',
                'message'                   => 'agentid invalid',
                'list'                      => array($this->res_trf_disburment_riwayat));
            $this->logAction('response', $traceid, $arr_res, 'Failed / data agent is empty');
            $this->response($arr_res);
        }              
       
        $res_mutasi = $this->Trf_model->Trf_disburment_mutasi($in_agent_id);
        //$res_listperday = false;
        $this->logAction('select', $traceid, array(), 'Trf_model->Trf_disburment_mutasi('.$in_agent_id.')');
        if($res_mutasi){
            $data = array();
            $arr = array();
            foreach ($res_mutasi as $val) {                
                $data['transferdate']           = $val->dtm;
                $data['sender_kodebank']        = $val->kode_bank_sender;
                $data['sender_namebank']        = $this->Trf_model->GetBankName($val->kode_bank_sender) ?: '';
                $data['sender_rekening']        = $val->rekening_sender;
                $data['sender_name']            = $val->nama_sender;
                $data['receiver_kodebank']      = $val->kode_bank_receiver;
                $data['receiver_namabank']      = $this->Trf_model->GetBankName($val->kode_bank_receiver) ?: '';;
                $data['receiver_rekening']      = $val->rekening_receiver;
                $data['receiver_name']          = $val->nama_receiver;
                $data['nominal_transfer']       = $this->Rp($val->nominal);                 
                $data['biaya_admin_transfer']   = $this->Rp($val->cost_adm + $val->cost_adm_bmt);                 
                $data['total']                  = $this->Rp($val->total);                 
                $data['msg_transfer']        = "BERHASIL";                 
                $arr[] = $data;
            }            
            $arr_res = array(
                'status'                    => TRUE,
                'errorcode'                 => '100',
                'message'                   => 'success',
                'list'                      => $arr);
            $this->response($arr_res);
        }else{
            $arr_res = array(
                'status'                    => FALSE,
                'errorcode'                 => '101',
                'message'                   => 'Riwayat kosong',
                'list'                      => array($this->res_trf_disburment_riwayat));
            $this->response($arr_res);            
        }        
    }
 
    public function Sent_trf_post() {
        $trace_id       = $this->logid();
        $this->logheader($trace_id);
	$in_agentid     = $this->input->post('agentid');
        $in_code        = $this->input->post('code');
        //***************************
        
        if(strlen($in_code) < 10 || strlen($in_code) > 40){
            $data = array('status' => FALSE,'errorcode'=>'111','message' => 'isempty agentid');
            $this->logAction('response',$trace_id,$data,'error data agent is too long so trx rejected :lenght('.  strlen($in_code));
            $this->response($data);
        }
        if(empty($in_code))        {
            $data = array('status' => FALSE,'errorcode'=>'112','message' => 'isempty code');
            $this->logAction('response',$trace_id,$data,'failed, code trasnfer is empty');
            $this->response($data);
        }
        if(empty($in_agentid))        {
            $data = array('status' => FALSE,'errorcode'=>'111','message' => 'isempty agentid');
            $this->logAction('response',$trace_id,$data,'failed,error data agent is empty');
            $this->response($data);
        }        
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array('status' => FALSE,'errorcode'=>'114','message' => 'invalid agentid');
            $this->logAction('response',$trace_id,$data,'failed, agent not found in databases ');
            $this->response($data);
        }
        
        $res_call_tiad_trf = $this->Tab_model->Tiad_trf_ses($in_agentid,$in_code);
        $this->logAction('get tiad session',$trace_id,array(),'Tab_model->Tiad_trf_ses('.$in_agentid.','.$in_code.')');
        if(!$res_call_tiad_trf){
            // aku rubah yang ini pri  echo  "4|expired";
            $data = array('status' => FALSE,'errorcode'=>'115','message' => 'invalid codetransfer');
            $this->logAction('response',$trace_id,$data,'code transfer not found in databases');
            $this->response($data);
        }
	$this->logAction('result',$trace_id,$res_call_tiad_trf,'get data tiad transfer');	
        foreach($res_call_tiad_trf as $sub_call_tiad_trf){
            $gsender_rekening       = $sub_call_tiad_trf->rekening_sender;
            $greceiver_rekening     = $sub_call_tiad_trf->rekening_receiver;
            $gsender_nominal        = $sub_call_tiad_trf->nominal;
            $gsender_cost_adm       = $sub_call_tiad_trf->cost_adm ?: 0;
            $gsender_total          = $sub_call_tiad_trf->total;
            $ggen_code              = $sub_call_tiad_trf->code_transfer;
            //$gsender_rekening       = $sub_call_tiad_trf->rekening_sender;
        }
        $result_data_sender = $this->Tab_model->Tab_pro_nas_join($gsender_rekening);
        $this->logAction('get data',$trace_id,array(),'rek:sender -- Tab_model->Tab_pro_nas_join('.$gsender_rekening.')');
        if($result_data_sender){
            foreach($result_data_sender as $sub_res_sender){
                $sender_no_rekening         = $sub_res_sender->NO_REKENING;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->SALDO_AKHIR;             
                $sender_alamat              = $sub_res_sender->alamat;             
            }    
            $this->logAction('result',$trace_id,array(),'rek sender : '.$sender_no_rekening.'//'.$sender_nama_nasabah.'//'.$sender_saldo.'//'.$sender_alamat);      
        }
        else{
            
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');
            $this->logAction('response',$trace_id,$data,'rekening sender '.$gsender_rekening.' not found in databases');
            $this->response($data);      
        }
        $result_data_receiver = $this->Tab_model->Tab_pro_nas_join($greceiver_rekening);
        $this->logAction('result',$trace_id,array(),'rek:receiver -- Tab_model->Tab_pro_nas_join('.$greceiver_rekening.')');
        
        if($result_data_receiver){
            foreach($result_data_receiver as $sub_res_receiver){
                $receiver_no_rekening         = $sub_res_receiver->NO_REKENING;                
                $receiver_nama_nasabah        = $sub_res_receiver->nama_nasabah;
                $receiver_saldo               = $sub_res_receiver->SALDO_AKHIR;             
                $receiver_alamat              = $sub_res_receiver->alamat;             
            }     
            $this->logAction('result',$trace_id,array(),'rek receiver : '.$receiver_no_rekening.'//'.$receiver_nama_nasabah.'//'.$receiver_saldo.'//'.$receiver_alamat);
        }
        else{
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');
            $this->logAction('response',$trace_id,$data,'rekening receiver '.$greceiver_rekening.' not found in databases');
            $this->response($data);     
        }
        
        if(abs($sender_saldo) > abs($gsender_total)){
            $this->logAction('result',$trace_id,array(),'cek saldo : sender saldo rekening: '.abs($sender_saldo).' Nominal transfer :'.abs($gsender_total));
            $this->logAction('result',$trace_id,array(),'cek saldo : result OK');
        }else{
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');            
            $this->logAction('response',$trace_id,$data,'Insufficent balace, saldo:'.abs($sender_saldo).' Nominal transfer:'.abs($gsender_nominal));
            $this->response($data);
        }
        //hitung jumlah saldo pengirim
        $sender_sisa_saldo = abs($sender_saldo) - abs($gsender_total);
        $receiver_jumlah_saldo = abs($receiver_saldo) + abs($gsender_nominal);
        
        $this->LogAction('info', $trace_id, array(), ' sender : saldo awal : '.abs($sender_saldo).' - '.abs($gsender_total).'='.$sender_sisa_saldo);
        $this->LogAction('info', $trace_id, array(), ' receiver : saldo awal : '.abs($receiver_saldo).' - '.abs($gsender_nominal).'='.$receiver_jumlah_saldo);
        //insert_tab_trans sender
        $gen_id_TABTRANS_ID_sender          = $this->App_model->Gen_id();
        $gen_id_COMMON_ID_sender            = $this->App_model->Gen_id();
        $gen_id_MASTER_sender               = $this->App_model->Gen_id();
        $in_desc_trans_sender               = $this->_Kodetrans_by_desc(TRANSFER_LOCAL_KIRIM_CODE);
        $in_tob_sender                      = $this->_Kodetrans_by_tob(TRANSFER_LOCAL_KIRIM_CODE);
        $in_keterangan_sender               = $in_desc_trans_sender." : an ".$sender_no_rekening." ".$sender_nama_nasabah." ke ".$receiver_no_rekening." ".$receiver_nama_nasabah." ".$gsender_nominal." Adm:".$gsender_cost_adm;
        
        //receiver
        $gen_id_TABTRANS_ID_receiver        = $this->App_model->Gen_id();
        $gen_id_COMMON_ID_receiver          = $this->App_model->Gen_id();
        $gen_id_MASTER_receiver             = $this->App_model->Gen_id();        
        $in_desc_trans_receiver             = $this->_Kodetrans_by_desc(TRANSFER_LOCAL_TERIMA_CODE);
        $in_tob_receiver                    = $this->_Kodetrans_by_tob(TRANSFER_LOCAL_TERIMA_CODE);
        $in_keterangan_receiver             = $in_desc_trans_receiver." : an ".$receiver_no_rekening." ".$receiver_nama_nasabah." dari ".$sender_no_rekening." ".$sender_nama_nasabah." ".$gsender_nominal;
        
        $in_KUITANSI    = $this->_Kuitansi();
        
        $arr_data = array(array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID_sender, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$sender_no_rekening, 
            'MY_KODE_TRANS'     =>TRANSFER_LOCAL_KIRIM_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$gsender_total,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan_sender, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agentid, 
            'KODE_TRANS'        =>TRANSFER_LOCAL_KIRIM_CODE,
            'TOB'               =>$in_tob_sender, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID_sender
            ),array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID_receiver, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$receiver_no_rekening, 
            'MY_KODE_TRANS'     =>TRANSFER_LOCAL_TERIMA_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$gsender_nominal,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan_receiver, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agentid, 
            'KODE_TRANS'        =>TRANSFER_LOCAL_TERIMA_CODE,
            'TOB'               =>$in_tob_receiver, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID_receiver
            ));
            
        $res_ins    = $this->Tab_model->Ins_batch('TABTRANS',$arr_data);
         if(!$res_ins){
            $data = array('status' => FALSE,'message' => 'error db');
            $this->logAction('insert',$trace_id,$arr_data,'failed,insert data TABTRANS');
            $this->response($data);
            return;
         }
        $this->logAction('update', $trace_id, array(), 'update saldo sender : Tab_model->Saldo_upd_by_rekening('.$sender_no_rekening.')');
        $this->Tab_model->Saldo_upd_by_rekening($sender_no_rekening);
       
        $this->logAction('update', $trace_id, array(), 'update saldo receiver : Tab_model->Saldo_upd_by_rekening('.$receiver_no_rekening.')');
        $this->Tab_model->Saldo_upd_by_rekening($receiver_no_rekening);

        $sender_res_call_tab   = $this->Tab_model->Tab_byrek($sender_no_rekening);
        if($sender_res_call_tab){
            foreach($sender_res_call_tab as $sender_sub_call_tab){
                $sender_in_kode_integrasi =  $sender_sub_call_tab->KODE_INTEGRASI;
            }
        }
        $receiver_res_call_tab   = $this->Tab_model->Tab_byrek($receiver_no_rekening);
        if($receiver_res_call_tab){
            foreach($receiver_res_call_tab as $receiver_sub_call_tab){
                $receiver_in_kode_integrasi =  $receiver_sub_call_tab->KODE_INTEGRASI;
            }
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agentid);
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
        $sender_res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($sender_in_kode_integrasi);
        foreach ($sender_res_call_tab_integrasi_by_kd as $sender_sub_call_tab_integrasi_by_kd) {

            $sender_in_kode_perk_kas_in_integrasi          =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
            $sender_in_kode_perk_hutang_pokok_in_integrasi =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
            $sender_in_kode_perk_pend_adm_in_integrasi     =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
            //$sender_in_kode_perk_penjualan                 =  $sender_sub_call_tab_integrasi_by_kd->kode_perk_penjualan;                                                      
            $sender_in_kode_perk_transfer                  =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_TRANSFER ?: '';                                                      
        }
        //receiver
        $receiver_res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($receiver_in_kode_integrasi);
        foreach ($receiver_res_call_tab_integrasi_by_kd as $sender_sub_call_tab_integrasi_by_kd) {

            $receiver_in_kode_perk_kas_in_integrasi          =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
            $receiver_in_kode_perk_hutang_pokok_in_integrasi =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
            $receiver_in_kode_perk_pend_adm_in_integrasi     =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
        }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }

        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
            $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: 'TAB';
        }
        $arr_master = array(
            array( //sender
                'TRANS_ID'          =>  $gen_id_MASTER_sender, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan_sender, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID_sender, 
                'USERID'            =>  $in_agentid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            ),
            array( //recevier
                'TRANS_ID'          =>  $gen_id_MASTER_receiver, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan_receiver, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID_receiver, 
                'USERID'            =>  $in_agentid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            ));
        $res_trans_master    = $this->Tab_model->Ins_batch('TRANSAKSI_MASTER',$arr_master);
            if(!$res_trans_master){
                $this->logAction('insertbatch',$trace_id,$arr_master,'failed,Insert to TRANSAKSI_MASTER');
                $data = array('status' => FALSE,'message' => 'error db');
                $this->response($data);
                return;
            }
        $this->logAction('insertbatch',$trace_id,$arr_master,'success, Insert to TRANSAKSI_MASTER');
        
        $ar_trans_detail = array(
            array( //sender
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_sender, 
                'KODE_PERK'     =>  $sender_in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $gsender_total ?: 0                                                             
            ),
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_sender, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $gsender_nominal ?: 0, 
                'KREDIT'        =>  0
            ),
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_sender, 
                'KODE_PERK'     =>  $sender_in_kode_perk_transfer ?: KODEPERKTRANSFER_DEFAULT, 
                'DEBET'         =>  $gsender_cost_adm ?: 0, 
                'KREDIT'        =>  0
            ),
            array( //receiver
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_receiver, 
                'KODE_PERK'     =>  $receiver_in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  $gsender_nominal ?: 0, 
                'KREDIT'        =>  0                                                             
            ),
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_receiver, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $gsender_nominal ?: 0
            )
        );
        $this->load->helper('date');
        $res_ins_detail = $this->Tab_model->Ins_batch('TRANSAKSI_DETAIL',$ar_trans_detail);
        if($res_ins_detail){
                $upd_ins =    array( //rek
                                'status' => '2',
                                'last_upd'  => mdate('%Y-%m-%d %H:%i:%s', time()),
                                'master_id_sender'  => $gen_id_MASTER_sender,
                                'master_id_receiver'  => $gen_id_MASTER_receiver,
                );
                $this->Tab_model->Upd_transfer($in_code,$upd_ins);
                $this->logAction('update',$trace_id,$upd_ins,'success, update status transfer');
                $this->logAction('insertbatch',$trace_id,$ar_trans_detail,'Insert to TRANSAKSI_DETAIL');
                
                
        }else{
            $this->logAction('insertbatch',$trace_id,$ar_trans_detail,'failed, Insert to TRANSAKSI_DETAIL');
        }
        $in_addpoin = array(
            'tid' => $gen_id_TABTRANS_ID_sender,
            'agent' => $in_agentid,
            'kode' => TRANSFER_LOCAL_TERIMA_CODE,
            'jenis' => 'TAB',
            'nilai' => $gsender_nominal
            );
        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
        if($res_poin){
            $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
        }else{
            $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
        }
        $data = array('status' => TRUE,'errorcode'=>'100','message' => 'Succesfully');
        $this->logAction('response', $trace_id,$data, 'Transaction is Done');
        $this->response($data);
        //return;
              
    }
    public function Sent_trf_offline_post() {
        $trace_id       = $this->logid();
        $this->logheader($trace_id);
	$in_agentid     = $this->input->post('agentid');
        $in_rek_sender              = $this->input->post('rek_sender') ?: '';
        $in_rek_receiver            = $this->input->post('rek_receiver') ?: '';
        $in_codetransfer            = $this->input->post('codetransfer') ?: '';               
        $in_nominal                 = $this->input->post('nominal') ?: '';   
        //***************************

        if(empty($in_agentid))        {
            $data = array('status' => FALSE,'errorcode'=>'111','message' => 'isempty agentid','codesage' => '');
            $this->logAction('response',$trace_id,$data,'failed,error data agent is empty');
            $this->response($data);
        }      
        if(empty($in_nominal)){    
            $data = array('status' => FALSE,'errorcode'=>'112','message' => 'nominal isempty','codesage' => '');
            $this->logAction('response', $traceid, $data, 'Failed / nominal is empty');
            $this->response($data);
        }
        if(empty($in_rek_sender)){            
            $data = array('status' => FALSE,'errorcode'=>'113','message' => 'rek sender isempty','codesage' => '');
            $this->logAction('response', $traceid, $data, 'Failed / rek sender is empty');
            $this->response($data);
        }
        if(empty($in_rek_receiver)){     
            $data = array('status' => FALSE,'errorcode'=>'114','message' => 'rek receiver isempty','codesage' => '');
            $this->logAction('response', $traceid, $data, 'Failed / rek receiver is empty');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array('status' => FALSE,'errorcode'=>'115','message' => 'invalid agentid','codesage' => '');
            $this->logAction('response',$trace_id,$data,'failed, agent not found in databases ');
            $this->response($data);
        }
        
        //=============================
        $res_call_tiad_trf = $this->Check_rek_offline($trace_id, $in_agentid, $in_rek_sender, $in_rek_receiver, $in_codetransfer, $in_nominal);
       // $res_call_tiad_trf = json_decode($res_call_tiad_trf);
        //$res_call_tiad_trf = $this->Tab_model->Tiad_trf_ses($in_agentid,$in_code);
        $this->logAction('get tiad session',$trace_id,array(),'Check_rek_offline('.$trace_id.','.$in_agentid.','. $in_rek_sender.','.$in_rek_receiver.'.'.$in_codetransfer.','. $in_nominal.')');
        if(!$res_call_tiad_trf){
            // aku rubah yang ini pri  echo  "4|expired";
            $data = array('status' => FALSE,'errorcode'=>'120','message' => 'error internal','codesage' => '');
                $this->logAction('response',$trace_id,$data,'invalid cek function cek_rek_offline');
            $this->response($data);
        }
	$this->logAction('result',$trace_id,array(),'get data tiad transfer'.' |'.$res_call_tiad_trf);	
        
        $sub_call_tiad_trf = $this->_Jdecode($res_call_tiad_trf);
        $gsender_rekening       = $sub_call_tiad_trf->sender_no_rekening;
        $greceiver_rekening     = $sub_call_tiad_trf->receiver_no_rekening;
        $gsender_nominal        = $sub_call_tiad_trf->nominal;
        $gsender_cost_adm       = $sub_call_tiad_trf->adm ?: 0;
        $gsender_total          = $sub_call_tiad_trf->total;
        $in_code                = $sub_call_tiad_trf->code;
       
        $result_data_sender = $this->Tab_model->Tab_pro_nas_join($gsender_rekening);
        $this->logAction('get data',$trace_id,array(),'rek:sender -- Tab_model->Tab_pro_nas_join('.$gsender_rekening.')');
        if($result_data_sender){
            foreach($result_data_sender as $sub_res_sender){
                $sender_no_rekening         = $sub_res_sender->NO_REKENING;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->SALDO_AKHIR;             
                $sender_alamat              = $sub_res_sender->alamat;             
            }    
            $this->logAction('result',$trace_id,array(),'rek sender : '.$sender_no_rekening.'//'.$sender_nama_nasabah.'//'.$sender_saldo.'//'.$sender_alamat);      
        }
        else{
            
            $data = array('status' => FALSE,'errorcode'=>'120','message' => 'error internal','codesage' => '');
            $this->logAction('response',$trace_id,$data,'rekening sender '.$gsender_rekening.' not found in databases');
            $this->response($data);      
        }
        $result_data_receiver = $this->Tab_model->Tab_pro_nas_join($greceiver_rekening);
        $this->logAction('result',$trace_id,array(),'rek:receiver -- Tab_model->Tab_pro_nas_join('.$greceiver_rekening.')');
        
        if($result_data_receiver){
            foreach($result_data_receiver as $sub_res_receiver){
                $receiver_no_rekening         = $sub_res_receiver->NO_REKENING;                
                $receiver_nama_nasabah        = $sub_res_receiver->nama_nasabah;
                $receiver_saldo               = $sub_res_receiver->SALDO_AKHIR;             
                $receiver_alamat              = $sub_res_receiver->alamat;             
            }     
            $this->logAction('result',$trace_id,array(),'rek receiver : '.$receiver_no_rekening.'//'.$receiver_nama_nasabah.'//'.$receiver_saldo.'//'.$receiver_alamat);
        }
        else{
            $data = array('status' => FALSE,'errorcode'=>'120','message' => 'error internal','codesage' => '');
            $this->logAction('response',$trace_id,$data,'rekening receiver '.$greceiver_rekening.' not found in databases');
            $this->response($data);     
        }
        
        if(abs($sender_saldo) > abs($gsender_total)){
            $this->logAction('result',$trace_id,array(),'cek saldo : sender saldo rekening: '.abs($sender_saldo).' Nominal transfer :'.abs($gsender_total));
            $this->logAction('result',$trace_id,array(),'cek saldo : result OK');
        }else{
            $data = array('status' => FALSE,'errorcode'=>'120','message' => 'error internal','codesage' => '');            
            $this->logAction('response',$trace_id,$data,'Insufficent balace, saldo:'.abs($sender_saldo).' Nominal transfer:'.abs($gsender_nominal));
            $this->response($data);
        }
        //hitung jumlah saldo pengirim
        $sender_sisa_saldo = abs($sender_saldo) - abs($gsender_total);
        $receiver_jumlah_saldo = abs($receiver_saldo) + abs($gsender_nominal);
        
        $this->LogAction('info', $trace_id, array(), ' sender : saldo awal : '.abs($sender_saldo).' - '.abs($gsender_total).'='.$sender_sisa_saldo);
        $this->LogAction('info', $trace_id, array(), ' receiver : saldo awal : '.abs($receiver_saldo).' - '.abs($gsender_nominal).'='.$receiver_jumlah_saldo);
        //insert_tab_trans sender
        $gen_id_TABTRANS_ID_sender          = $this->App_model->Gen_id();
        $gen_id_COMMON_ID_sender            = $this->App_model->Gen_id();
        $gen_id_MASTER_sender               = $this->App_model->Gen_id();
        $in_desc_trans_sender               = $this->_Kodetrans_by_desc(TRANSFER_LOCAL_KIRIM_CODE);
        $in_tob_sender                      = $this->_Kodetrans_by_tob(TRANSFER_LOCAL_KIRIM_CODE);
        $in_keterangan_sender               = $in_desc_trans_sender." : an ".$sender_no_rekening." ".$sender_nama_nasabah." ke ".$receiver_no_rekening." ".$receiver_nama_nasabah." ".$gsender_nominal." Adm:".$gsender_cost_adm;
        
        //receiver
        $gen_id_TABTRANS_ID_receiver        = $this->App_model->Gen_id();
        $gen_id_COMMON_ID_receiver          = $this->App_model->Gen_id();
        $gen_id_MASTER_receiver             = $this->App_model->Gen_id();        
        $in_desc_trans_receiver             = $this->_Kodetrans_by_desc(TRANSFER_LOCAL_TERIMA_CODE);
        $in_tob_receiver                    = $this->_Kodetrans_by_tob(TRANSFER_LOCAL_TERIMA_CODE);
        $in_keterangan_receiver             = $in_desc_trans_receiver." : an ".$receiver_no_rekening." ".$receiver_nama_nasabah." dari ".$sender_no_rekening." ".$sender_nama_nasabah." ".$gsender_nominal;
        
        $in_KUITANSI    = $this->_Kuitansi();
        
        $arr_data = array(array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID_sender, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$sender_no_rekening, 
            'MY_KODE_TRANS'     =>TRANSFER_LOCAL_KIRIM_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$gsender_total,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan_sender, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agentid, 
            'KODE_TRANS'        =>TRANSFER_LOCAL_KIRIM_CODE,
            'TOB'               =>$in_tob_sender, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID_sender
            ),array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID_receiver, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
            'NO_REKENING'       =>$receiver_no_rekening, 
            'MY_KODE_TRANS'     =>TRANSFER_LOCAL_TERIMA_MYCODE, 
            'KUITANSI'          =>$in_KUITANSI, 
            'POKOK'             =>$gsender_nominal,
            'ADM'               =>ADM_DEFAULT,
            'KETERANGAN'        =>$in_keterangan_receiver, 
            'VERIFIKASI'        =>'1', 
            'USERID'            =>$in_agentid, 
            'KODE_TRANS'        =>TRANSFER_LOCAL_TERIMA_CODE,
            'TOB'               =>$in_tob_receiver, 
            'SANDI_TRANS'       =>'',
            'KODE_PERK_OB'      =>'', 
            'NO_REKENING_VS'    =>'', 
            'KODE_KOLEKTOR'     =>'000',
            'KODE_KANTOR'       =>KODE_KANTOR_DEFAULT,
            'ADM_PENUTUPAN'     =>'0.00',
            'COMMON_ID'         =>$gen_id_COMMON_ID_receiver
            ));
            
        $res_ins    = $this->Tab_model->Ins_batch('TABTRANS',$arr_data);
         if(!$res_ins){
            $data = array('status' => FALSE,'errorcode'=>'120','message' => 'error internal','codesage' => '');            
            $this->logAction('insert',$trace_id,$arr_data,'failed,insert data TABTRANS');
            $this->response($data);
            return;
         }
        $this->logAction('update', $trace_id, array(), 'update saldo sender : Tab_model->Saldo_upd_by_rekening('.$sender_no_rekening.')');
        $this->Tab_model->Saldo_upd_by_rekening($sender_no_rekening);
       
        $this->logAction('update', $trace_id, array(), 'update saldo receiver : Tab_model->Saldo_upd_by_rekening('.$receiver_no_rekening.')');
        $this->Tab_model->Saldo_upd_by_rekening($receiver_no_rekening);

        $sender_res_call_tab   = $this->Tab_model->Tab_byrek($sender_no_rekening);
        if($sender_res_call_tab){
            foreach($sender_res_call_tab as $sender_sub_call_tab){
                $sender_in_kode_integrasi =  $sender_sub_call_tab->KODE_INTEGRASI;
            }
        }
        $receiver_res_call_tab   = $this->Tab_model->Tab_byrek($receiver_no_rekening);
        if($receiver_res_call_tab){
            foreach($receiver_res_call_tab as $receiver_sub_call_tab){
                $receiver_in_kode_integrasi =  $receiver_sub_call_tab->KODE_INTEGRASI;
            }
        }

        $res_call_kode_perk_kas = $this->Sys_daftar_user_model->Perk_kas($in_agentid);
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
        $sender_res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($sender_in_kode_integrasi);
        foreach ($sender_res_call_tab_integrasi_by_kd as $sender_sub_call_tab_integrasi_by_kd) {

            $sender_in_kode_perk_kas_in_integrasi          =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
            $sender_in_kode_perk_hutang_pokok_in_integrasi =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
            $sender_in_kode_perk_pend_adm_in_integrasi     =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
            //$sender_in_kode_perk_penjualan                 =  $sender_sub_call_tab_integrasi_by_kd->kode_perk_penjualan;                                                      
            $sender_in_kode_perk_transfer                  =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_TRANSFER ?: '';                                                      
        }
        //receiver
        $receiver_res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($receiver_in_kode_integrasi);
        foreach ($receiver_res_call_tab_integrasi_by_kd as $sender_sub_call_tab_integrasi_by_kd) {

            $receiver_in_kode_perk_kas_in_integrasi          =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_KAS ?: '';
            $receiver_in_kode_perk_hutang_pokok_in_integrasi =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK ?: '';
            $receiver_in_kode_perk_pend_adm_in_integrasi     =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM ?: '';                                                      
        }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }

        $res_call_tab_keyvalue = $this->Sysmysysid_model->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        foreach($res_call_tab_keyvalue as $sub_call_tab_keyvalue){
            $res_kode_jurnal = $sub_call_tab_keyvalue->KEYVALUE ?: 'TAB';
        }
        $arr_master = array(
            array( //sender
                'TRANS_ID'          =>  $gen_id_MASTER_sender, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan_sender, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID_sender, 
                'USERID'            =>  $in_agentid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            ),
            array( //recevier
                'TRANS_ID'          =>  $gen_id_MASTER_receiver, 
                'KODE_JURNAL'       =>  $res_kode_jurnal, 
                'NO_BUKTI'          =>  $in_KUITANSI, 
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
                'URAIAN'            =>  $in_keterangan_receiver, 
                'MODUL_ID_SOURCE'   =>  $res_kode_jurnal, 
                'TRANS_ID_SOURCE'   =>  $gen_id_TABTRANS_ID_receiver, 
                'USERID'            =>  $in_agentid, 
                'KODE_KANTOR'       =>  KODE_KANTOR_DEFAULT
            ));
        $res_trans_master    = $this->Tab_model->Ins_batch('TRANSAKSI_MASTER',$arr_master);
            if(!$res_trans_master){
                $this->logAction('insertbatch',$trace_id,$arr_master,'failed,Insert to TRANSAKSI_MASTER');
                $data = array('status' => TRUE,'errorcode'=>'100','message' => 'Succesfully','codesave' => $in_code);
                $this->response($data);
                return;
            }
        $this->logAction('insertbatch',$trace_id,$arr_master,'success, Insert to TRANSAKSI_MASTER');
        
        $ar_trans_detail = array(
            array( //sender
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_sender, 
                'KODE_PERK'     =>  $sender_in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $gsender_total ?: 0                                                             
            ),
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_sender, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  $gsender_nominal ?: 0, 
                'KREDIT'        =>  0
            ),
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_sender, 
                'KODE_PERK'     =>  $sender_in_kode_perk_transfer ?: KODEPERKTRANSFER_DEFAULT, 
                'DEBET'         =>  $gsender_cost_adm ?: 0, 
                'KREDIT'        =>  0
            ),
            array( //receiver
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_receiver, 
                'KODE_PERK'     =>  $receiver_in_kode_perk_hutang_pokok_in_integrasi, 
                'DEBET'         =>  $gsender_nominal ?: 0, 
                'KREDIT'        =>  0                                                             
            ),
            array(
                'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                'MASTER_ID'     =>  $gen_id_MASTER_receiver, 
                'KODE_PERK'     =>  $in_kode_perk_kas, 
                'DEBET'         =>  0, 
                'KREDIT'        =>  $gsender_nominal ?: 0
            )
        );
        $this->load->helper('date');
        $res_ins_detail = $this->Tab_model->Ins_batch('TRANSAKSI_DETAIL',$ar_trans_detail);
        if($res_ins_detail){
                $upd_ins =    array( //rek
                                'status' => '2',
                                'last_upd'  => mdate('%Y-%m-%d %H:%i:%s', time()),
                                'master_id_sender'  => $gen_id_MASTER_sender,
                                'master_id_receiver'  => $gen_id_MASTER_receiver,
                );
                $this->Tab_model->Upd_transfer($in_code,$upd_ins);
                $this->logAction('update',$trace_id,$upd_ins,'success, update status transfer');
                $this->logAction('insertbatch',$trace_id,$ar_trans_detail,'Insert to TRANSAKSI_DETAIL');
                
                
        }else{
            $this->logAction('insertbatch',$trace_id,$ar_trans_detail,'failed, Insert to TRANSAKSI_DETAIL');
        }
        $in_addpoin = array(
            'tid' => $gen_id_TABTRANS_ID_sender,
            'agent' => $in_agentid,
            'kode' => TRANSFER_LOCAL_TERIMA_CODE,
            'jenis' => 'TAB',
            'nilai' => $gsender_nominal
            );
        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
        if($res_poin){
            $this->logAction('update', $trace_id, array(), 'result :OK');
        }else{
            $this->logAction('update', $trace_id, array(), 'result :NOK');
        }
        $data = array('status' => TRUE,'errorcode'=>'100','message' => 'Succesfully','codesave' => $in_code);
        $this->logAction('response', $trace_id,$data, 'Transaction is Done');
        $this->response($data);
        //return;
              
    }
    public function _tarik($in_NO_REKENING = '',$in_nominal = 0,$in_adm = 0,$in_USERID = '',$in_rek_receiver = '',$in_kode_bank_receiver = '',$trace_id = '', $tipetrf = '') {

                 
        $trace_id   = $trace_id ?: $this->logid();
        //$this->logheader($trace_id);
        if(empty($in_NO_REKENING) || empty($in_nominal) || empty($in_USERID)){
            return FALSE;
        }
        $in_kd_kantor           = kode_kantor_default;
        $in_adm_penutupan       = '';       
        $in_SANDI_TRANS         = setor_sandi;       
        $in_kd_trans            = tarik_kode_trans;
        $in_mykdetrans          = tarik_my_kode_trans;
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
        $in_KUITANSI        = $this->_Kuitansi();
        $in_keterangan      = $this->_Kodetrans_by_desc($in_kd_trans);
        $in_tob             = $this->_Kodetrans_by_tob($in_kd_trans);
        $in_keterangan      = $in_keterangan." : an ".$in_NO_REKENING." KE ".$in_kode_bank_receiver." ".$in_rek_receiver." ".  $this->Rp($in_POKOK);
        $unitkerja          = $in_kd_kantor;
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
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
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
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
                    'DEBET'         =>  $in_nominal, 
                    'KREDIT'        =>  0                                                             
                ),  
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, // kas kasanah
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_POKOK
                )  
            );
            
            $ar_trans_detail_dsb = array(
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_kas, 
                    'DEBET'         =>  $in_POKOK, 
                    'KREDIT'        =>  0
                ),     
                array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  KODEPERK_ANRO_DEFAULT, 
                    'DEBET'         =>  0, 
                    'KREDIT'        =>  $in_POKOK                                                             
                )                           
            );
            
            $ar_trans_detail_adm = array(
                    'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                    'MASTER_ID'     =>  $gen_id_MASTER, 
                    'KODE_PERK'     =>  $in_kode_perk_transfer ?: KODEPERKTRANSFER_DEFAULT, 
                    'DEBET'         =>  $in_adm ?: 0, 
                    'KREDIT'        =>  0
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
        $in_KUITANSI        = $this->_Kuitansi();
        $in_keterangan      = $this->_Kodetrans_by_desc($in_kd_trans);
        $in_tob             = $this->_Kodetrans_by_tob($in_kd_trans);
        $in_keterangan      = $in_keterangan." KE ".$in_kode_bank_receiver." ".$in_rek_receiver." ".  $this->Rp($in_POKOK);
        $unitkerja          = $in_kd_kantor;
        
        $arr_data = array(
            'TABTRANS_ID'       =>$gen_id_TABTRANS_ID, 
            'TGL_TRANS'         =>$this->_Tgl_hari_ini(), 
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
                'TGL_TRANS'         =>  $this->_Tgl_hari_ini(), 
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
    function _cek_saldo($in_saldo_rekening = '',$in_nominal = '') {
        if(empty($in_saldo_rekening)) { return FALSE;}
        if(empty($in_nominal)) { return FALSE;}
        if($in_saldo_rekening > $in_nominal){
            return TRUE;
        }        
        return FALSE;
    }
    function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    function _Kuitansi() {
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
                $out_nokwi = increment_string($out_nokwi,'.');
            }
            return $out_nokwi;
    }
    function _Kodetrans_by_desc($in_kd_trans = NULL) {
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
    function _Kodetrans_by_tob($in_kd_trans = NULL) {
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
    public function Listbank_get() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $res_call_daftar_bank = $this->Tab_model->Bank();
        if($res_call_daftar_bank){
            $this->logAction('response', $trace_id, $res_call_daftar_bank, 'OK');
            $this->response($res_call_daftar_bank);            
            return;
        }
        $this->logAction('response', $trace_id, '', 'failed query Tab_model->Bank()');
        return FALSE;    
        
    }    
    function Rp($value = '')    {
        return number_format((float)$value,0,",",".");
    }
    function Hitget($url)    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        echo $output;
    }
    function HitPostAJ($url = '',$post_fields  = array()) {
        
        $headers    = array(
            'x-api-key:KSUETt0GbE3Ac1',
            'x-api-pass:086ae0e690c3731b814223f8ab2f508c82a2f10b',
            'Content-Type:application/json'
        );
        $post_fields = json_encode($post_fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $data;

    }    
}

define('urlinq', 'http://103.75.103.126/payment/gw/api/disbursement/inquiry');
define('urltrf', 'http://103.75.103.126/payment/gw/api/disbursement/transfer');