<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Antarbank
 *
 * @author edisite
 */
class Antarbank extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Poin_model');
    }
    public function Ver_transfer_bmt() {
        $crud = $this->generate_crud('transfer_ses_e');
        $crud->set_theme('datatables');
        $crud->columns('dtm', 'kode_transfer', 'agent_id', 'rekening_sender', 'nama_sender', 'rekening_receiver', 'nama_sender', 'kode_bank_receiver', 'nominal');
        $crud->display_as('dtm', 'Waktu');
        $crud->display_as('agent_id', 'Agen');
        $crud->display_as('rekening_sender', 'Rek. Kirim');
        $crud->display_as('nama_sender', 'Nama Kirim');
        $crud->display_as('rekening_receiver', 'Rek. Tujuan');
        $crud->display_as('nama_receiver', 'Nama Tujuan');
        $crud->display_as('kode_bank_receiver', 'Bank Tujuan');
        $crud->where('kode_transfer', '100');
        $crud->where('status', '1');
        $crud->callback_column('nominal', array($this, 'rp'));
        $crud->add_action('Proses', 'show_button', base_url() . 'admin/administrator/managemen_users/delete/id/');
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_edit();
        $crud->unset_delete();
        $this->mTitle .= '[1015] transfer antar Rekening';
        $this->render_crud();
    }
    public function Ver_transfer_antar_bank() {
        $crud = $this->generate_crud('transfer_ses_e');
        $crud->set_model('transfer_agen_model');
        $crud->set_theme('datatables');
        $crud->columns('dtm', 'kode_transfer', 'agent_id', 'rekening_sender', 'nama_sender', 'rekening_receiver', 'nama_sender', 'kode_bank_receiver', 'nominal');
        $crud->fields('dtm', 'kode_transfer');
        $crud->where('transfer_ses_e.kode_transfer', '101');
        $crud->where('transfer_ses_e.status', '1');
        $crud->display_as('dtm', 'Waktu');
        $crud->display_as('agent_id', 'Agen');
        $crud->display_as('rekening_sender', 'Rek. Kirim');
        $crud->display_as('nama_sender', 'Nama Kirim');
        $crud->display_as('rekening_receiver', 'Rek. Tujuan');
        $crud->display_as('nama_receiver', 'Nama Tujuan');
        $crud->display_as('kode_bank_receiver', 'Bank Tujuan');
        $crud->callback_column('nominal', array($this, 'rp'));
        $crud->add_action('Proses', 'show_button', base_url() . 'admin/trf/antarbank/procantar_bank/');
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_edit();
        $crud->unset_delete();
        $this->mTitle .= '[1016] Verifikasi transfer ke Bank lain';
        $this->render_crud();
    }
    function Rp($value, $row = '') {
        return number_format($value, 2, ",", ".");
    }
    public function TrfBmtToOtherBank()    {
        $this->mViewData['listBank']=$this->Tab_model->Bank();
        $this->render('transfer/transToOtherBank');
    }
    public function TransAntarLainBMT()     {$this->mViewData['listBank']=$this->Tab_model->Bank();
        $this->render('transfer/transAntarLainBMT');
     }
    public function TrfAntarRekeningLokal() {//danial 16022017

        $this->mViewData['user'] = $this->ion_auth->user()->row();
        $this->mViewData['dtm'] = "ISENG";
        $this->mViewData['fromRequest'] = $this->input->get('fromRequest');
        $this->render('transfer/transLocal');
        
    }
    public function GetRekeningByNameRek() {
        $getSearchValue = $this->input->get('term');
        //log_message("ERROR", $getSearchValue);
        $dataNasabah = $this->Nas_model->Tab_src_nas($getSearchValue);
        //$this->logAction('response', "0912-03912-309", $dataNasabah, 'NGEEENG');
        $array = array();
        foreach($dataNasabah as $objek)
        {
            $array[] = array(
                'label' => $objek->nama . ',' . $objek->rekening,
                'value' => $objek->rekening,
                );

        }

        echo json_encode($array);
    }
    function _Jdecode($data) {
        return json_decode($data);
    }
    public function CheckRekening() {//danial 16022017
        $res_kode_bank_transfer = $this->Trf_model->Cek_bank_default();
        $in_rek_sender = $this->input->post('rekPengirim');
        $in_rek_receiver = $this->input->post('rekPenerima');
        $in_nominal = $this->input->post('nominalTransfer');
        $in_agent_id = $this->ion_auth->get_user_id();
        $in_codetransfer = 100;
        $in_kode_bank_sender = $res_kode_bank_transfer;
        $in_kode_bank_receiver = $res_kode_bank_transfer;
        $res_call_jenis_trf = $this->Trf_model->Model_transfer($in_codetransfer);        
                   
        $cost_adm_transfer = 0;
        $masterid   = '';
        if ($res_call_jenis_trf) {
            foreach ($res_call_jenis_trf as $sub_jenis_trf) {
                $set_adm_default = $sub_jenis_trf->set_adm_default;
                $adm_default = $sub_jenis_trf->adm_default;
                $set_limit = $sub_jenis_trf->set_limit;
                $min_trf = $sub_jenis_trf->min_trf;
                $max_trf = $sub_jenis_trf->max_trf;
            }
        }
        if (strtolower($set_adm_default) == "bydefault") {
            $cost_adm_transfer = $adm_default;
        } elseif (strtolower($set_adm_default) == "byreceiverbank") {
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_receiver);
            if ($res_cek_bank) {
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }
            }
        } elseif (strtolower($set_adm_default) == "bysenderbank") {
            $res_cek_bank = $this->Trf_model->Cekbank($in_kode_bank_sender);
            if ($res_cek_bank) {
                foreach ($res_cek_bank as $sub_cek_bank) {
                    $cost_adm_transfer = $sub_cek_bank->biaya_adm;
                }
            }
        }



        $tarif_code = "";
        $in_status_session = 0;

        $datasender = $this->_Cekrek($in_rek_sender);
        if ($datasender) {
            $sub_res_sender = $this->_Jdecode($datasender);
            $sender_no_rekening = $sub_res_sender->norekening;
            $sender_nama_nasabah = $sub_res_sender->nama_nasabah;
            $sender_saldo = $sub_res_sender->saldo;
            $sender_alamat = $sub_res_sender->alamat;
            $SENDERSTATUS = $sub_res_sender->status;
            $sender_errorstatus = $sub_res_sender->message;
        }
        $datareceiver = $this->_Cekrek($in_rek_receiver);
        if ($datareceiver) {
            $sub_res_receiver = $this->_Jdecode($datareceiver);
            $receiver_no_rekening = $sub_res_receiver->norekening;
            $receiver_nama_nasabah = $sub_res_receiver->nama_nasabah;
            $receiver_saldo = $sub_res_receiver->saldo;
            $receiver_alamat = $sub_res_receiver->alamat;
            $RECEIVERSTATUS = $sub_res_receiver->status;
            $receiver_errorstatus = $sub_res_receiver->message;
        }
       if($SENDERSTATUS == TRUE && $RECEIVERSTATUS == TRUE){
            $in_status_session =  '1';
            $status = TRUE;  
            if(empty($arr_message)){
                $arr_message = '';
            }
            //$this->logAction('check status', $traceid, array('msg ' => $arr_message), 'Sender & Receiver is complete');                
        }else{
            $arr_message    = 'problem internal';  
           // $this->logAction('check status', $traceid, array('msg ' => $arr_message.' Salah'), 'Sender & Receiver not complete');
            $this->Res_cek_rek('120', $arr_message);
        }

//
        $id_transfer = trim($in_agent_id) . $this->_rand_id();
        $gettotal = $in_nominal + $cost_adm_transfer;

        $arr = array(
            'status' => TRUE,
            'errorcode' => '100',
            'message' => trim($arr_message),
            'sender_no_rekening' => $sender_no_rekening,
            'sender_nama_nasabah' => $sender_nama_nasabah,
            'sender_kode_bank' => $in_kode_bank_sender,
            'sender_alamat' => $sender_alamat,
            'receiver_no_rekening' => $receiver_no_rekening,
            'receiver_nama_nasabah' => $receiver_nama_nasabah,
            'receiver_kode_bank' => $in_kode_bank_receiver,
            'receiver_alamat' => $receiver_alamat,
            'nominal' => $this->Rp($in_nominal),
            'adm' => $this->Rp($cost_adm_transfer),
            'total' => $this->Rp($gettotal),
            'tarif_code' => $tarif_code,
            'code' => $id_transfer,
        );
$arr_inst   = array(
            'agent_id'              => $in_agent_id,
            'rekening_sender'       => $sender_no_rekening,
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
            'total'                 => $this->Rp($gettotal),
            'status'                => $in_status_session,
            'ip'                    => "localhost",
            'usr_agent'             => "Unidentified User Agent",
            'code_transfer'         => $id_transfer,
            'master_id_sender'      => $masterid,
            'tarif_code'            => $tarif_code,
            'respon_json'           => json_encode($arr, TRUE),
            'mesg_desc_sys'         => 'SENDER:'.$sender_errorstatus.' || RECEIVER:'.$receiver_errorstatus,
        );
//
      $this->Tab_model->Ins_Tbl('transfer_ses_e', $arr_inst);

        echo json_encode($arr);
    }
    public function Sent_trf()    { //danial 16022017
        $in_agentid = $this->ion_auth->get_user_id();
        $in_code = $this->input->post('code') ?: '';
        
        //log_message('DEBUG', 'LINE 242');
        
        $res_call_tiad_trf = $this->Tab_model->Tiad_trf_ses($in_agentid,$in_code);
       
        if(!$res_call_tiad_trf){
            // aku rubah yang ini pri  echo  "4|expired";
            $data = array('status' => FALSE,'errorcode'=>'115','message' => 'invalid codetransfer');
           
           
        }
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
        
      //  echo json_encode($result_data_sender);
        
        if($result_data_sender){
            foreach($result_data_sender as $sub_res_sender){
                $sender_no_rekening         = $sub_res_sender->NO_REKENING;                
                $sender_nama_nasabah        = $sub_res_sender->nama_nasabah;
                $sender_saldo               = $sub_res_sender->SALDO_AKHIR;             
                $sender_alamat              = $sub_res_sender->alamat;             
            }    
            
        
        }
        else{
            
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');
           
            
        }
        
        $result_data_receiver = $this->Tab_model->Tab_pro_nas_join($greceiver_rekening);
//       echo json_encode($result_data_receiver); 
         if($result_data_receiver){
            foreach($result_data_receiver as $sub_res_receiver){
                $receiver_no_rekening         = $sub_res_receiver->NO_REKENING;                
                $receiver_nama_nasabah        = $sub_res_receiver->nama_nasabah;
                $receiver_saldo               = $sub_res_receiver->SALDO_AKHIR;             
                $receiver_alamat              = $sub_res_receiver->alamat;             
            }     
           
        }
        else{
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');
           
        }
        
         if(abs($sender_saldo) > abs($gsender_total)){
            
        }else{
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error internal');            
          
        
        }
        
//        //hitung jumlah saldo pengirim
        $sender_sisa_saldo = abs($sender_saldo) - abs($gsender_total);
        $receiver_jumlah_saldo = abs($receiver_saldo) + abs($gsender_nominal);
    // echo $receiver_jumlah_saldo;   
//        //insert_tab_trans sender
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
         
        // echo $in_KUITANSI;
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
            //echo json_encode($arr_data);
        $res_ins    = $this->Tab_model->Ins_batch('TABTRANS',$arr_data);
         if(!$res_ins){
            $data = array('status' => FALSE,'message' => 'error db');
            
            return;
         }
      //   echo json_encode($res_ins);
         $sender_res_sum12  = $this->Tab_model->Sum_1_2_taptrans($sender_no_rekening);
      //   echo json_encode($sender_res_sum12);
        if(!$sender_res_sum12){
            $data = array('status' => FALSE,'message' => 'error db');
            return;
        }
     
        foreach ($sender_res_sum12 as $sender_sub12) {
            $sender_setoran12      = $sender_sub12->SETORAN;
            $sender_penarikan12    = $sender_sub12->PENARIKAN;
            $sender_setoran_bunga12    = $sender_sub12->SETORAN_BUNGA;
            $sender_penarikan_bunga12    = $sender_sub12->PENARIKAN_BUNGA;
        }
        $receiver_res_sum12  = $this->Tab_model->Sum_1_2_taptrans($receiver_no_rekening);
     
    //   echo json_encode($receiver_res_sum12);
        if(!$receiver_res_sum12){
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error db');
     
            return;
        }
        
 
        foreach ($receiver_res_sum12 as $receiver_sub12) {
            $receiver_setoran12      = $receiver_sub12->SETORAN;
            $receiver_penarikan12    = $receiver_sub12->PENARIKAN;
            $receiver_setoran_bunga12    = $receiver_sub12->SETORAN_BUNGA;
            $receiver_penarikan_bunga12    = $receiver_sub12->PENARIKAN_BUNGA;
        }
        $sender_hasil      = $sender_setoran12 - $sender_penarikan12;
        $sender_bunga      = $sender_setoran_bunga12 - $sender_penarikan_bunga12;                        
        $receiver_hasil      = $receiver_setoran12 - $receiver_penarikan12;
        $receiver_bunga      = $receiver_setoran_bunga12 - $receiver_penarikan_bunga12;                        
        $sender_datatabung =   array( //rekening sender
                                'SALDO_AKHIR' => $sender_hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $sender_bunga);
        $receiver_datatabung =    array( //rek
                                'SALDO_AKHIR' => $receiver_hasil,
                                'STATUS' => '1',
                                'SALDO_AKHIR_TITIPAN_BUNGA' => $receiver_bunga);   
       //  echo json_encode($receiver_datatabung);
        
        $sender_res_upd_tab = $this->Tab_model->upd_tabung($sender_no_rekening,$sender_datatabung);
        if(!$sender_res_upd_tab){   
            
            $data = array('status' => FALSE,'message' => 'error db');
           
            return;
        }
        
       // echo $sender_res_upd_tab;
        $receiver_res_upd_tab = $this->Tab_model->upd_tabung($receiver_no_rekening,$receiver_datatabung);
        if(!$receiver_res_upd_tab){                            
            $data = array('status' => FALSE,'errorcode'=>'116','message' => 'error db');
           
            return;
        }
//      
        $sender_res_call_tab   = $this->Tab_model->Tab_byrek($sender_no_rekening);
        if($sender_res_call_tab){
            foreach($sender_res_call_tab as $sender_sub_call_tab){
                $sender_in_kode_integrasi =  $sender_sub_call_tab->KODE_INTEGRASI;
            }
        }
        //echo json_encode($sender_res_call_tab);
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
       // echo json_encode($res_call_kode_perk_kas);
         $sender_res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($sender_in_kode_integrasi);
        foreach ($sender_res_call_tab_integrasi_by_kd as $sender_sub_call_tab_integrasi_by_kd) {

            $sender_in_kode_perk_kas_in_integrasi          =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
            $sender_in_kode_perk_hutang_pokok_in_integrasi =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
            $sender_in_kode_perk_pend_adm_in_integrasi     =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
            //$sender_in_kode_perk_penjualan                 =  $sender_sub_call_tab_integrasi_by_kd->kode_perk_penjualan;                                                      
            $sender_in_kode_perk_transfer                  =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_TRANSFER;                                                      
        }
        //receiver
        $receiver_res_call_tab_integrasi_by_kd = $this->Tab_model->Integrasi_by_kd_int($receiver_in_kode_integrasi);
        foreach ($receiver_res_call_tab_integrasi_by_kd as $sender_sub_call_tab_integrasi_by_kd) {

            $receiver_in_kode_perk_kas_in_integrasi          =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
            $receiver_in_kode_perk_hutang_pokok_in_integrasi =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_HUTANG_POKOK;
            $receiver_in_kode_perk_pend_adm_in_integrasi     =  $sender_sub_call_tab_integrasi_by_kd->KODE_PERK_PEND_ADM;                                                      
        }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }
        $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($in_kode_perk_kas,$in_gord);
        foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
            $count_as_total = $sub_call_perk_kode_all->total;
        }
//        echo json_encode($res_call_perk_kode_all);
        $this->load->model('Sysmysysid_model','SysModel');
        $res_call_tab_keyvalue = $this->SysModel->Key_by_keyname('ACC_KODE_JURNAL_TABUNGAN');
        //echo json_encode($res_call_tab_keyvalue);
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
       // echo json_encode($arr_master);
        $res_trans_master    = $this->Tab_model->Ins_batch('TRANSAKSI_MASTER',$arr_master);
       // echo json_encode($res_trans_master);
        
            if(!$res_trans_master){
               
                $data = array('status' => FALSE,'message' => 'error db');
               
                return;
            }
//       
//        
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
       // echo json_encode($ar_trans_detail);
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
        
                
                
        }else{
        
        }
        //echo json_encode($res_ins_detail);
        $in_addpoin = array(
            'tid' => $gen_id_TABTRANS_ID_sender,
            'agent' => $in_agentid,
            'kode' => TRANSFER_LOCAL_TERIMA_CODE,
            'jenis' => 'TAB',
            'nilai' => $gsender_nominal
            );
        //echo json_encode($in_addpoin);
        
        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
     //   echo json_encode($res_poin);
       $data = array('status' => TRUE,'errorcode'=>'100','message' => 'Succesfully');
        
//        $this->response($data);/
        //return;
        echo json_encode($data);
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
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
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
    protected function _rand_id() {
        $in_random = random_string('alnum', 20);
        if (empty($in_random)) {
            $in_random = random_string('alnum', 20);
        }
        return date('ymdHi') . $in_random;
    }
    function _Cekrek($in_norekening) {
        $result_data = $this->Tab_model->Tab_pro_nas_join($in_norekening);
        if ($result_data) {
            foreach ($result_data as $sub_res_sender) {
                $sender_nasabah_id = $sub_res_sender->nasabah_id;
                $sender_no_rekening = $sub_res_sender->NO_REKENING;
                $sender_nama_nasabah = $sub_res_sender->nama_nasabah;
                $sender_saldo = $sub_res_sender->SALDO_AKHIR;
                $sender_alamat = $sub_res_sender->alamat;
            }
            $sender_errorstatus = "";
            $SENDERSTATUS = TRUE;
        } else {
            $SENDERSTATUS = FALSE;
            $sender_errorstatus = "no_rekening";
            $sender_no_rekening = "";
            $sender_nama_nasabah = "";
            $sender_alamat = "";
            $sender_saldo = 0;
        }
        $data = array(
            "norekening" => $sender_no_rekening,
            "nama_nasabah" => $sender_nama_nasabah,
            "alamat" => $sender_alamat,
            "saldo" => $sender_saldo,
            "status" => $SENDERSTATUS,
            "message" => $sender_errorstatus,
        );
//            return $this->response($data); 


        return json_encode($data);
    }
    public function Ver_transfer_masuk_bmt() {
        $crud = $this->generate_crud('transfer_ses_e');
        $crud->set_theme('datatables');
        $crud->columns('dtm', 'kode_transfer', 'agent_id', 'rekening_sender', 'nama_sender', 'rekening_receiver', 'nama_sender', 'kode_bank_receiver', 'nominal');
        $crud->fields('dtm', 'kode_transfer');
        $crud->where('kode_transfer', '102');
        $crud->where('status', '1');
        $crud->display_as('dtm', 'Waktu');
        $crud->display_as('agent_id', 'Agen');
        $crud->display_as('rekening_sender', 'Rek. Kirim');
        $crud->display_as('nama_sender', 'Nama Kirim');
        $crud->display_as('rekening_receiver', 'Rek. Tujuan');
        $crud->display_as('nama_receiver', 'Nama Tujuan');
        $crud->display_as('kode_bank_receiver', 'Bank Tujuan');
        $crud->callback_column('nominal', array($this, 'rp'));
        $crud->add_action('Proses', 'show_button', base_url() . 'admin/trf/antarbank/procantar_bank/');
        $crud->unset_add();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_edit();
        $crud->unset_delete();
        $this->mTitle .= '[1017] Verifikasi Transfer dari Bank lain ke Rek. BMT';
        $this->render_crud();
    }
    public function Procantar_bank($in_id_transfer = NULL) {
        if (empty($in_id_transfer)) {
            redirect(base_url() . 'admin/trf/antarbank/ver_transfer_masuk_bmt');
            return;
        }
        $res_session_transfer = $this->Trf_model->Trf_bank($in_id_transfer);
        if ($res_session_transfer) {
            foreach ($res_session_transfer as $sub_session_transfer) {
                $ses_pegawe = $sub_session_transfer->pegawe;
                $ses_agent_id = $sub_session_transfer->agent_id;
                $ses_bank_sender = $sub_session_transfer->bank_pengirim;
                $ses_rekening_sender = $sub_session_transfer->rekening_sender;
                $ses_nama_sender = $sub_session_transfer->nama_sender;
                $ses_bank_receiver = $sub_session_transfer->bank_penerima;
                $ses_rekening_receiver = $sub_session_transfer->rekening_receiver;
                $ses_nama_receiver = $sub_session_transfer->nama_receiver;
                $ses_nominal = $sub_session_transfer->nominal;
                $ses_cost_adm = $sub_session_transfer->cost_adm;
                $ses_total = $sub_session_transfer->total;
                $ses_code_transfer = $sub_session_transfer->code_transfer;
                $ses_dtm = $sub_session_transfer->dtm;
                //id_transfer,agent_id,dtm,kode_transfer,rekening_sender,kode_bank_sender
            }
        } else {
            redirect(base_url() . 'admin/trf/antarbank/ver_transfer_antar_bank');
            return;
        }
        $get_saldo = $this->Tab_model->Tab_pro_nas_join($ses_rekening_sender);
        //print_r($get_saldo);
        if (!$get_saldo) {
            redirect(base_url() . 'admin/trf/antarbank/ver_transfer_antar_bank');
            return;
        }
        $this->mViewData['agent_nama'] = $ses_pegawe ? : '';
        $this->mViewData['agentid'] = $ses_agent_id ? : '';
        $this->mViewData['bank_sender'] = $ses_bank_sender ? : '';
        $this->mViewData['rek_sender'] = $ses_rekening_sender ? : '';
        $this->mViewData['nama_sender'] = $ses_nama_sender ? : '';
        $this->mViewData['bank_receiver'] = $ses_bank_receiver ? : '';
        $this->mViewData['rek_receiver'] = $ses_rekening_receiver ? : '';
        $this->mViewData['nama_receiver'] = $ses_nama_receiver ? : '';
        $this->mViewData['nominal'] = $this->Rp($ses_nominal ? : 0);
        $this->mViewData['adm'] = $this->Rp($ses_cost_adm ? : 0);
        $this->mViewData['code_transfer'] = $ses_code_transfer ? : '';
        $this->mViewData['dtm'] = $ses_dtm ? : '';
        $this->mTitle = '[1018] Proses Transfer';
        $this->render('transfer/confirm_page_dari_bmt_ke_bank_lain');
    }
    public function Procantar_bank_upd() {
        //?tgl=&jam=tes&berita=tes&ref=&status=success&keterangan=
        $in_tgl = $this->input->post('tgl') ? : '';
        $in_jam = $this->input->post('jam') ? : '';
        $in_berita = $this->input->post('berita') ? : '';
        $in_ref = $this->input->post('ref') ? : '';
        $in_status = $this->input->post('status') ? : '';
        $in_keterangan = $this->input->post('keterangan') ? : '';
        $in_code = $this->input->post('code') ? : '';
        if ($in_status == "succes") {
            $getstatus = "2";
        } else {
            $getstatus = "1";
        }

        $upd_ins = array(//rek
            'status' => $getstatus,
            't_tgl' => mdate('%Y-%m-%d', $in_tgl),
            't_jam' => $in_jam,
            't_berita' => $in_berita,
            't_noref' => $in_ref,
            't_status' => $in_status,
            't_keterangan' => $in_keterangan,
            'last_upd' => mdate('%Y-%m-%d %H:%i:%s', time()),
            't_admin_id' => $this->session->userdata('username')
        );

        $this->Tab_model->Upd_transfer($in_code, $upd_ins);
        redirect(base_url() . 'admin/trf/antarbank/ver_transfer_antar_bank');
    }
}
define('TRANSFER_LOCAL_KIRIM_CODE', '225');
define('TRANSFER_LOCAL_KIRIM_MYCODE', '200');
define('TRANSFER_LOCAL_TERIMA_CODE', '125');
define('TRANSFER_LOCAL_TERIMA_MYCODE', '100');
define('KODE_KANTOR_DEFAULT', '35');
define('ADM_DEFAULT', '0.00');
define('KODEPERKKAS_DEFAULT', '10101');
define('KODEPERKTRANSFER_DEFAULT', '409');
define('tarik_my_kode_trans', '200');
define('tarik_kode_trans', '226');
define('setor_sandi', '01');
define('tarik_sandi', '02');
define('veririfikasi', '1');
define('kode_kolektor', '000');
define('kode_kantor_default', '35');
