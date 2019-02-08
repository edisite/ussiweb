<?php
//errorcode 600
define('DEFAULT_KODE_PERK_KAS', '10101');
define('DEFAULT_KODE_TRANS', '300');
define('DEFAULT_KANTOR', '35');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kredit
 *
 * @author edisite
 */
class Kredit extends API_Controller{
    
    protected $count_trans = 0;
    protected $message = 'BELUM LUNAS';    
    const LUNAS = 600;
    protected $jml_pinjaman = 0;
    
    protected $res_kredit_pay  = array(
            'plafon'        => 0,//jml_pinjaman
            'angsuran_ke'   => '',
            'setoran_pokok' => 0, // setoran pokok
            'setoran_jasa'  => 0, //bunga
            'sisa_saldo'    => 0 //sisa pinjaman
        );
    
    //error code
    //protected $errorcode['LUNAS'] = 'ANGSURAN LUNAS';
    //put your code here
    public function __construct() {
        parent::__construct();                    
    }
    public function Cektes_post() {
        //$in_nomor_rekening = $this->input->post('rekid');
        $in_param = array(
            'tid' => '9242842',
            'agent' => '23242',
            'kode' => '100',
            'jenis' => 'TAB',
            'nilai' => '10000',
        );
        $data = $this->Poin_model->Ins_his_reward(json_encode($in_param));
        return $data;
    }
    public function Cek_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_nomor_rekening  = $this->input->post('rekid') ?: '';
        $in_agentid         = $this->input->post('agentid') ?: '';
        if(empty($in_nomor_rekening)){
            $data = array(
                'status' => FALSE,
                'error_code' =>610,
                'message' => 'Nomor rekening isempty',
                'data_nasabah' => '',
                'data_tunggakan' => '',
                'data_tagihan'   => '',
                'sisa_pinjaman'     => '',
                'total_pokok'     => '',
                'total_bunga'     => '',
                'total_setoran'     => '',
                'code_angsuran'  => ''
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            $this->response($data);
            
        }
        if(empty($in_agentid)){
            $data = array(
                'status' => FALSE,
                'error_code' =>612,
                'message' => 'agentid isempty',
                'data_nasabah' => '',
                'data_tunggakan' => '',
                'data_tagihan'  => '',
                'sisa_pinjaman'     => '',
                'total_pokok'     => '',
                'total_bunga'     => '',
                'total_setoran'     => '',
                'code_angsuran'  => ''
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array(
                'status' => FALSE,
                'error_code' =>613,
                'message' => 'agentid invalid',
                'data_nasabah' => '',
                'data_tunggakan' => '',
                'data_tagihan'   => '',
                'sisa_pinjaman'     => '',
                'total_pokok'     => '',
                'total_bunga'     => '',
                'total_setoran'     => '',
                'code_angsuran'  => ''
                );
            $this->logAction('response', $trace_id, $data, 'Failed / data agent not found in databases');
            $this->response($data);
        }
        //query data nasabah
        $result_data_nasabah = $this->Kre_model->Kre_nas_join_by_rek($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_nas_join_by_rek('.$in_nomor_rekening.')');       
        $arr = array();
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $arr['no_rekening']         = $sub_res->NO_REKENING;
                $get_norekening             = $sub_res->NO_REKENING;
                $arr['nama_nasabah']        = $sub_res->nama_nasabah;
                $get_nama_nasabah           = $sub_res->nama_nasabah;
                $arr['jml_pinjaman']        = $this->Rp($sub_res->JML_PINJAMAN) ?: 0;
                $arr['tgl_pencairan']       = $sub_res->TGL_REALISASI;
                $arr['tgl_jth_tempo']       = $sub_res->TGL_JATUH_TEMPO;
                $tgl_jatuh_tempo            = $sub_res->TGL_JATUH_TEMPO;
                $arr['jml_angsuran']        = $sub_res->JML_ANGSURAN;
                $jml_angsuran               = $sub_res->JML_ANGSURAN;
                $arr['tgl_tagihan']         = $sub_res->TGL_TAGIHAN;                        
                $tgl_tagihan                = $sub_res->TGL_TAGIHAN;                        
                $arr['desc_kredit']         = $sub_res->DESKRIPSI_TYPE_KREDIT;
                
                $arr['nisbah']         = $sub_res->SUKU_BUNGA_PER_TAHUN;                                             
            }
            $this->logAction('result', $trace_id, $arr, 'success');            
        }
        else{          
             $data = array(
                'status' => FALSE,
                'error_code' =>611,
                'message' => 'Nomor rekening invalid',
                'data_nasabah' => '',
                'data_tunggakan' => '',
                'data_tagihan'   => '',
                'sisa_pinjaman'     => '',
                'total_pokok'     => '',
                'total_bunga'     => '',
                'total_setoran'     => '',
                'code_angsuran'  => ''
                );
        
            $this->response($data);
            $this->logAction('response', $trace_id, $data, 'failed, data not found in database');            
        }
        $result_count_trans = $this->Kre_model->Count_trans_by_kodetrans($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Count_trans_by_kodetrans('.$in_nomor_rekening.')');       
        if($result_count_trans){
            foreach($result_count_trans as $sub_res_count){
                $this->count_trans        = $sub_res_count->counttrans;                                          
            }
            $this->logAction('result', $trace_id, $result_count_trans, 'success');            
        }
        if($this->count_trans > 0){          
             $data = array(
                'status' => FALSE,
                'error_code' =>614,
                'message' => 'Double transaksi anggsuran',
                'data_nasabah' => '',
                'data_tunggakan' => '',
                'data_tagihan'   => '',
                'sisa_pinjaman'     => '',
                'total_pokok'     => '',
                'total_bunga'     => '',
                'total_setoran'     => '',
                'code_angsuran'  => ''
                );
             $this->logAction('response', $trace_id, $data, 'Hari ini sudah bayar anggsuran');    
            $this->response($data);
                    
        }
        
        $result_angsuranke = $this->Kre_model->Kre_angsuran_ke_by_rek($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_angsuran_ke_by_rek('.$in_nomor_rekening.')');       
        if($result_angsuranke){
            foreach($result_angsuranke as $sub_ang){
                $angsuran_ke = $sub_ang->ANGSURAN_KE;
            }
        }
        if($angsuran_ke == ''){
            $angsuran_ke = 0;
        }
        
        $this->logAction('result', $trace_id, array(), 'Angsuran_ke : '.$angsuran_ke.' dari '.$jml_angsuran);       
        
        if($tgl_tagihan == ''){
            $tgl_tagihan = '01';
        }
        //query data tunggakan
        //$this->logAction('status angsuran', $trace_id, $data, 'jml angsuran :'.$jml_angsuran.' - '.$angsuran_ke);
        if(intval($angsuran_ke) >= intval($jml_angsuran)){
            $this->status  = "600";
            $this->message = "ANGSURAN LUNAS";            
        }else{
            $this->status  = "601";
            $this->message = "ANGSURAN BELUM LUNAS";
        }
        
        $res_get_all_tagihan = $this->Kre_model->Tag_tag_ang_detail($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Tag_tag_ang_detail('.$in_nomor_rekening.')');
        $this->logAction('result', $trace_id, $res_get_all_tagihan, 'cek data tagihan');
        if($res_get_all_tagihan){
            foreach($res_get_all_tagihan as $subtag){
                $RES_TAG_POKOK                = $subtag->TAG_POKOK;
                $RES_ANG_POKOK                = $subtag->ANG_POKOK;
                $RES_TAG_BUNGA                = $subtag->TAG_BUNGA;
                $RES_ANG_BUNGA                = $subtag->ANG_BUNGA;                                                           
                $RES_TAG_DENDA                = $subtag->TAG_DENDA;                                                           
                $RES_ANG_DENDA                = $subtag->ANG_DENDA;                                                           
                $RES_TAG_ADM_LAINNYA          = $subtag->TAG_ADM_LAINNYA;                                                           
                $RES_ANG_ADM_LAINNYA          = $subtag->ANG_ADM_LAINNYA;                                                           
            }
            
            $htpokok = intval($RES_TAG_POKOK) - intval($RES_ANG_POKOK);
            $htbunga = intval($RES_TAG_BUNGA) - intval($RES_ANG_BUNGA);
            $htdenda = intval($RES_TAG_DENDA) - intval($RES_ANG_DENDA);
            $htadm   = intval($RES_TAG_ADM_LAINNYA) - intval($RES_ANG_ADM_LAINNYA);
            if($htpokok       > 0){}else{ $htpokok          = 0;           }
            if($htbunga       > 0){}else{ $htbunga          = 0;           }
            if($htdenda       > 0){}else{ $htdenda          = 0;           }
            if($htadm       > 0){}else{ $htadm          = 0;           }
            
            $arrtunggakan = array();
            $arrtunggakan['message']    = "Tunggakan s.d ".date('j F, Y', strtotime('last day of previous month'));
            $arrtunggakan['pokok']    = $this->Rp(0);
            $arrtunggakan['bunga']    = $this->Rp(0);                      
            $arrtunggakan['denda']    = $this->Rp(0);                      
            $arrtunggakan['adm']      = $this->Rp(0);                     
            $this->logAction('result', $trace_id, $arrtunggakan, 'success');
            
            $arrtagihan = array();
            $arrtagihan['message']    = "Tagihan ke: [".$angsuran_ke."] [".$this->Star_date()." - ".$this->Last_date($tgl_tagihan)."]";          
            $arrtagihan['pokok']    = $this->Rp($htpokok);
            $arrtagihan['bunga']    = $this->Rp($htbunga);                      
            $arrtagihan['denda']    = $this->Rp($htdenda);                      
            $arrtagihan['adm']      = $this->Rp($htadm); 
            $this->logAction('result', $trace_id, $arrtagihan, 'success');
            
            if($htpokok == 0 && $htbunga == 0 && $htdenda == 0 && $htadm == 00){
                $data = array(
                'status' => TRUE,
                'error_code' => $this->status,
                'message' => $this->message,
                'data_nasabah' => $arr,
                'data_tunggakan' => $arrtunggakan,
                'data_tagihan'   => $arrtagihan,
                'sisa_pinjaman'     => '',
                'total_pokok'    => intval($htpokok),
                'total_bunga'    => intval($htbunga),
                'total_setoran'  => $this->Rp($htpokok + $htbunga),
                'code_angsuran'  => '',
                );
                $this->logAction('response', $trace_id, $data, 'Done'); 
                $this->response($data);
            }
            
        }
        $arrtunggakan = array();        
        $result_data_tunggakan = $this->Kre_model->Kre_tunggakan_by_rek($in_nomor_rekening,  $this->_Tgl_hari_ini());
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_tunggakan_by_rek('.$in_nomor_rekening.')');
        if($result_data_tunggakan){
            foreach($result_data_tunggakan as $sub_tunggakan){
                $tunggakan_pokok                = $sub_tunggakan->TUNGGAKAN_POKOK;
                $tunggakan_bunga                = $sub_tunggakan->TUNGGAKAN_BUNGA;
                $saldo_pokok                    = $sub_tunggakan->SALDO_POKOK;
                $saldo_bunga                    = $sub_tunggakan->SALDO_BUNGA;                                                           
                $saldo_denda                    = $sub_tunggakan->DENDA;                                                           
                $saldo_adm                      = $sub_tunggakan->ADM_LAINNYA;                                                           
            }
            if($tunggakan_pokok > 0){}else{ $tunggakan_pokok    = 0;           }
            if($tunggakan_bunga > 0){}else{ $tunggakan_bunga    = 0;           }
            if($saldo_pokok     > 0){}else{ $saldo_pokok        = 0;           }
            if($saldo_bunga     > 0){}else{ $saldo_bunga        = 0;           }
            if($saldo_denda     > 0){}else{ $saldo_denda        = 0;           }
            if($saldo_adm       > 0){}else{ $saldo_adm          = 0;           }
            
            $totaltunggakan = $tunggakan_pokok + $tunggakan_bunga + $saldo_denda + $saldo_adm;
            $arrtunggakan['message']    = "Tunggakan s.d ".date('j F, Y', strtotime('last day of previous month'));
            $arrtunggakan['pokok']    = $this->Rp($tunggakan_pokok);
            $arrtunggakan['bunga']    = $this->Rp($tunggakan_bunga);                      
            $arrtunggakan['denda']    = $this->Rp($saldo_denda);                      
            $arrtunggakan['adm']      = $this->Rp($saldo_adm);                     
            $this->logAction('result', $trace_id, $arrtunggakan, 'success');            
        }
        else{
             $data = array(
                'status'            => FALSE,
                'error_code'        => 615,
                'message'           => 'Error System',
                'data_nasabah'      => '',
                'data_tunggakan'    => '',
                'data_tagihan'      => '',
                'sisa_pinjaman'     => '',
                'total_pokok'       => '',
                'total_bunga'       => '',
                'total_setoran'     => '',
                'code_angsuran'     => ''
                );        
            
            $this->logAction('response', $trace_id, $data, 'failed, data not found in database'); 
            $this->response($data);
        }   
        
        $arrtagihan = array();
        $result_data_tagihan = $this->Kre_model->Tag_tag_bln($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Tag_tag_bln('.$in_nomor_rekening.')');
        
        if($result_data_tagihan){
            foreach($result_data_tagihan as $sub_tagihan){
                $tag_pokok                = $sub_tagihan->TAG_POKOK;
                $tag_bunga                = $sub_tagihan->TAG_BUNGA;
                $tag_denda                = $sub_tagihan->TAG_DENDA;
                $tag_adm                  = $sub_tagihan->TAG_ADM_LAINNYA;                                                           
            }
            if($tag_pokok > 0)  {       }else{ $tag_pokok        = 0; }
            if($tag_bunga > 0)  {       }else{ $tag_bunga        = 0; }
            if($tag_denda > 0)  {       }else{ $tag_denda        = 0; }
            if($tag_adm > 0)    {       }else{ $tag_adm          = 0; }
            
            $totaltag = $tag_pokok + $tag_bunga + $tag_denda + $tag_adm;
            $arrtagihan['message']    = "Tagihan ke: [".$angsuran_ke."] [".$this->Star_date()." - ".$this->Last_date($tgl_tagihan)."]";
            $arrtagihan['pokok']    = $this->Rp($tag_pokok);
            $arrtagihan['bunga']    = $this->Rp($tag_bunga);                      
            $arrtagihan['denda']    = $this->Rp($tag_denda);                      
            $arrtagihan['adm']      = $this->Rp($tag_adm);  
            
            $this->logAction('result', $trace_id, $arrtagihan, 'success');
            
            
        }
        else{  
            $data = array(
                'status'            => FALSE,
                'error_code'        => 615,
                'message'           => 'Error System',
                'data_nasabah'      => '',
                'data_tunggakan'    => '',
                'data_tagihan'      => '',
                'sisa_pinjaman'     => '',
                'total_pokok'     => '',
                'total_bunga'     => '',
                'total_setoran'     => '',
                'code_angsuran'  => ''
                );
            $this->logAction('response', $trace_id, $data, 'failed, data not found in database');   
            $this->response($data);
        }
        
        $id_angsuran = trim($in_agentid).$this->_rand_id();        
        $kre_angsuran = array(
                        'no_rekening'       => $get_norekening,
                        'nama'              => $get_nama_nasabah,
                        'total_setoran'     => $totaltunggakan + $totaltag,
                        'total_tunggakan'   => $totaltunggakan,
                        'tunggakan_pokok'   => $tunggakan_pokok,
                        'tunggakan_bunga'   => $tunggakan_bunga,
                        'tunggakan_adm'     => $saldo_adm,
                        'tunggakan_denda'   => $saldo_denda,
                        'total_tagihan'     => $totaltag,
                        'tagihan_pokok'     => $tag_pokok,
                        'tagihan_bunga'     => $tag_bunga,
                        'tagihan_adm'       => $tag_adm,
                        'tagihan_denda'     => $tag_denda,
                        'angsuran_ke'       => $angsuran_ke,
                        'jml_angsuran'      => $jml_angsuran,
                        'status'            => '0',
                        'keterangan'        => '1.request data',
                        'userid'            => $in_agentid,
                        'code_trx'          => $id_angsuran
            );
            $this->Kre_model->Ins_Tbl('kre_angsuran_ses_e',$kre_angsuran);
            $this->logAction('insert', $trace_id, $kre_angsuran, 'data session :Kre_model->Ins_Tbl(kre_angsuran_ses_e)'); 
        $data = array(
                'status' => TRUE,
                'error_code' => $this->status,
                'message' => $this->message,
                'data_nasabah' => $arr,
                'data_tunggakan' => $arrtunggakan,
                'data_tagihan'   => $arrtagihan,
                'sisa_pinjaman'  => $this->Rp($saldo_pokok),
                'total_pokok'    => intval($tunggakan_pokok + $tag_pokok),
                'total_bunga'    => intval($tunggakan_bunga + $tag_bunga),
                'total_setoran'  => $this->Rp($totaltunggakan + $totaltag),
                'code_angsuran'  => $id_angsuran,
                );
        $this->logAction('response', $trace_id, $data, 'Done'); 
        $this->response($data);
    }
    
    public function Jadwal_angsuran_get() {     
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_nomor_rekening = $this->input->get('rekid');
        $result_data_nasabah = $this->Kre_model->Jadwal_angsuran($in_nomor_rekening);
         $this->logAction('select', $trace_id, array(), 'Kre_model->Jadwal_angsuran('.$in_nomor_rekening.')');
        if($result_data_nasabah){   
            $this->logAction('response', $trace_id, $result_data_nasabah, 'success');
            $this->response($result_data_nasabah);
        }
        else{
            $data = array('status' => FALSE,'error' => 'rekid');
            $this->logAction('response', $trace_id, $data, 'failed, data not found in database');
            $this->response($data);
        }
        
    }
    public function Tagihan_get() {       
        $trace_id = $this->logid();
        $this->logheader($trace_id);
       $in_nomor_rekening = $this->input->get('rekid');
        //$in_tgl_trans = $this->input->get('tgl_trans');
       if(empty($in_nomor_rekening)){
           $data = array('status' => FALSE,'error' => 'rekid');
            $this->logAction('response', $trace_id, $data, 'failed, rekening is empty');
            $this->response($data);
       }
        $result_data_nasabah = $this->Kre_model->Tag_tag_ang_detail($in_nomor_rekening);
         $this->logAction('select', $trace_id, array(), 'Kre_model->Tag_tag_ang_detail('.$in_nomor_rekening.')');
        if($result_data_nasabah){            
            $this->logAction('response', $trace_id, $result_data_nasabah, 'success');
            $this->response($result_data_nasabah);
        }
        else{
            $data = array('status' => FALSE,'error' => 'rekid');
            $this->logAction('response', $trace_id, $data, 'failed, data not found in database');
            $this->response($data);
        }
        
    }
    public function Tagihan_tag_get() {  
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        $in_nomor_rekening = $this->input->get('rekid');
        $result_data_nasabah = $this->Kre_model->Tag_tag_bln($in_nomor_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Tag_tag_bln('.$in_nomor_rekening.')');
        if($result_data_nasabah){         
            $this->logAction('response', $trace_id, $result_data_nasabah, 'success');
            $this->response($result_data_nasabah);
        }
        else{
            $data = array('status' => FALSE,'error' => 'rekid');
            $this->logAction('response', $trace_id, $data, 'failed, data not found in database');
            $this->response($data);
        }
        
    }
    public function Sent_ang_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $in_agentid             = $this->input->post('agentid') ?: '';
        $in_code_trx            = $this->input->post('code_angsuran') ?: '';
        $in_nominal_pokok       = $this->input->post('nominal_pokok') ?: 0;
        $in_nominal_bunga       = $this->input->post('nominal_bunga') ?: 0;
        $in_no_rekening         = $this->input->post('rekid') ?: '';
        
        //cek parameter
        
        if(empty($in_no_rekening)){
            
            $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 610',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
        
            $data = array(
                'status' => FALSE,
                'error_code' =>610,
                'message' => 'Nomor rekening isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            
            
            $this->response($data);
            
        }
        if(empty($in_agentid)){
            
            $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 612',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>612,
                'message' => 'agentid isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 613',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            $data = array(
                'status' => FALSE,
                'error_code' =>613,
                'message' => 'agentid invalid',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Failed / data agent not found in databases');
            $this->response($data);
        }
        if(empty($in_code_trx)){
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 614',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>614,
                'message' => 'code angsuran isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'code_angsuran is empty');
            $this->response($data);
        }
        $this->logAction('response', $trace_id, array(), 'Nominal pokok : '.$in_nominal_pokok);
        /*if(!is_int($in_nominal_pokok)){
            $data = array(
                'status' => FALSE,
                'error_code' =>615,
                'message' => 'Nominal pokok isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Nominal pokok is empty');
            $this->response($data);
        }
        if(!is_int($in_nominal_bunga)){
            $data = array(
                'status' => FALSE,
                'error_code' =>622,
                'message' => 'Nominal bunga isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Nominal bunga is empty');
            $this->response($data);
        }*/
        if($this->Duwet_no_limit($in_nominal_pokok) == FALSE){
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 616',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>616,
                'message' => 'Invalid Nominal pokok ['.$in_nominal_pokok.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal_pokok.']');
            $this->response($data);
        }
        if($this->Duwet_no_limit($in_nominal_bunga) == FALSE){
            
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 623',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>623,
                'message' => 'Invalid Nominal bunga ['.$in_nominal_pokok.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal.']');
            $this->response($data);
        }
        if($in_nominal_pokok == 0 && $in_nominal_bunga == 0){
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 616',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>616,
                'message' => 'Invalid Nominal pokok ['.$in_nominal_pokok.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal_pokok.']');
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal_bunga.']');
            $this->response($data);
        }
        
        $ses_angsuran = $this->Kre_model->Kre_angsuran_ses($in_no_rekening,$in_code_trx,$in_agentid);
        if($ses_angsuran){
            foreach ($ses_angsuran as $sub_res_angsuran) {
                $total_setoran      = $sub_res_angsuran->total_setoran;
                $total_tunggakan    = $sub_res_angsuran->total_tunggakan;
                $total_tagihan      = $sub_res_angsuran->total_tagihan;
                $angsuran_ke        = $sub_res_angsuran->angsuran_ke;                
//                $t_pokok            = $sub_res_angsuran->t_pokok;                
//                $t_bunga            = $sub_res_angsuran->t_bunga;                
//                $t_denda            = $sub_res_angsuran->t_denda;                
//                $t_adm              = $sub_res_angsuran->t_adm;                
            }
            if($total_setoran == '' || $total_tunggakan == '' || $total_tagihan == '' || $angsuran_ke == ''){
                
                $arr_upd_ses = array(
                    'status' => '5',
                    'keterangan' => 'errorcode 617',
                    'lastupd' => date('Y-m-d H:i:s')
                    );
                $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
                
                $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
                $this->logAction('response', $trace_id, $data, 'Error Internal, Cannot empty // '
                        . 'total Setoran : '.$total_setoran.' '
                        . 'Total Tagihan : '.$total_tagihan.' '
                        . 'Total tunggakan : '.$total_tunggakan);
                $this->response($data);
            }
            
            $this->logAction('info', $trace_id, array(),'Total Tagihan: '.  $this->Rp($total_setoran).' '
                        . 'detail-- [[[[[#Total Tagihan : '.  $this->Rp($total_tagihan).' '
                        . '#Total tunggakan : '.  $this->Rp($total_tunggakan).']]]]] ');
            
            $this->logAction('info', $trace_id, array(),'Total Bayar: '.  $this->Rp($in_nominal_pokok + $in_nominal_bunga).' '
                        . 'detail-- [[[[[#Total POKOK : '.  $this->Rp($in_nominal_pokok).' '
                        . '#Total BUNGA : '.  $this->Rp($in_nominal_bunga).']]]]] ');
            
            $this->logAction('tracer', $trace_id, array(), 'Angsuran Terakhir : ('.$angsuran_ke.')');
            $angsuran_ke = $angsuran_ke + 1;
            $this->logAction('tracer', $trace_id, array(), 'Angsuran Berikutnya : ('.$angsuran_ke.')');
            
                  
        }else{            
            $data = array(
                'status' => FALSE,
                'error_code' =>619,
                'message' => 'Invalid Code Angsuran ['.$in_code_trx.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Code Angsuran ['.$in_code_trx.']');
            $this->response($data);
        }            
        $t_pokok            = $in_nominal_pokok ?: 0;                
        $t_bunga            = $in_nominal_bunga ?: 0;                
        $t_denda            = 0;                
        $t_adm              = 0; 
            
        $get_integrasi = $this->Kre_model->Kode_integrasi_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kode_integrasi_by_rek('.$in_no_rekening.')');
        if($get_integrasi){            
        }else{
            
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 617',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'kode integrasi');
        }
        foreach ($get_integrasi as $sub_integrasi){
            $in_kode_integrasi          = $sub_integrasi->kode_integrasi;
        }
        $this->logAction('result', $trace_id, array(), 'kode integrasi : '.$in_kode_integrasi);
        $res_call_kode_perk_kas         = $this->Sys_daftar_user_model->Perk_kas($in_agentid);
        $this->logAction('select', $trace_id, array(), 'Sys_daftar_user_model->Perk_kas('.$in_agentid.')');
            if($res_call_kode_perk_kas){
                foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                    $in_kode_perk_kas   = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                }
                $this->logAction('result', $trace_id, $data, 'kode perk kas : '.$in_kode_perk_kas);
            }
            if(empty($in_kode_perk_kas)){
                $in_kode_perk_kas = DEFAULT_KODE_PERK_KAS;
                $this->logAction('result', $trace_id, array(), 'kode perk kas [set default]: '.$in_kode_perk_kas);
            }
            
            $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
             $this->logAction('select', $trace_id, array(), 'Perk_model->perk_by_kodeperk('.$in_kode_perk_kas.')');
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk   = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord        = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            $res_call_tab_integrasi_by_kd = $this->Kre_model->Integrasi_by_kd_int($in_kode_integrasi);
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_kredit_in_integrasi       =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KREDIT;
                $in_kode_perk_bunga_in_integrasi        =  $sub_call_tab_integrasi_by_kd->KODE_PERK_BUNGA;                                                      
            }                 
            $res_call_perk_kode_kredit = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kredit_in_integrasi);
            if($res_call_perk_kode_kredit){
                foreach($res_call_perk_kode_kredit as $sub_call_perk_kode_kredit){
                    $in_nama_perk_kredit = $sub_call_perk_kode_kredit->NAMA_PERK;
                    $in_gord_kredit = $sub_call_perk_kode_kredit->G_OR_D;
                }
            }
            $res_call_perk_kode_bunga = $this->Perk_model->perk_by_kodeperk($in_kode_perk_bunga_in_integrasi);
            if($res_call_perk_kode_bunga){
                foreach($res_call_perk_kode_bunga as $sub_call_perk_kode_bunga){
                    $in_nama_perk_bunga         = $sub_call_perk_kode_bunga->NAMA_PERK;
                    $in_gord_bunga              = $sub_call_perk_kode_bunga->G_OR_D;
                }
            }
            
        $gen_id_KRETRANS_ID         = $this->App_model->Gen_id();
        $in_KUITANSI                = $this->_Kuitansi();
        $in_keterangan              = $this->_Kodetrans_by_desc(DEFAULT_KODE_TRANS);
        $in_tob                     = $this->_Kodetrans_by_tob(DEFAULT_KODE_TRANS);
        
        $this->logAction('result', $trace_id, array(), 'KUITANSI : '.$in_KUITANSI);
        $this->logAction('result', $trace_id, array(), 'KET : '.$in_keterangan);
        $this->logAction('result', $trace_id, array(), 'T O B : '.$in_tob);
        $res_cek_rek                = $this->Kre_model->Kre_nas_join_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_nas_join_by_rek('.$in_no_rekening.')');
        if(!$res_cek_rek){
             $arr_upd_ses = array(
                'status' => '5',
                'keterangan' => 'errorcode 617',
                'lastupd' => date('Y-m-d H:i:s')
            );
            $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
            
            $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('result', $trace_id, $data, '');
            $this->response($data);
            return;
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
        }
        $in_keterangan              = $in_keterangan." ".$angsuran_ke.", No Rek: ".$in_no_rekening.", ".$nama_nasabah;
        $this->logAction('descrp', $trace_id, array(), $in_keterangan);
        $unitkerja                  = DEFAULT_KANTOR;        
        $arr_KRETRANS = array(
            'KRETRANS_ID'           => $gen_id_KRETRANS_ID,
            'TGL_TRANS'             => $this->_Tgl_hari_ini(),
            'NO_REKENING'           => $in_no_rekening,
            'MY_KODE_TRANS'         => DEFAULT_KODE_TRANS,
            'KUITANSI'              => $in_KUITANSI,
            'ANGSURAN_KE'           => $angsuran_ke,
            'POKOK'                 => $t_pokok,
            'BUNGA'                 => $t_bunga,
            'DENDA'                 => $t_denda,
            'ADM_LAINNYA'           => $t_adm,
            'KETERANGAN'            => $in_keterangan,
            'VERIFIKASI'            => '1',
            'USERID'                => $in_agentid,
            'KODE_TRANS'            => DEFAULT_KODE_TRANS,
            'NO_REKENING_TABUNGAN'  => NULL,
            'TOB'                   => $in_tob,
            'ACCRUAL'               => '',
            'Kolek'                 => NULL,
            'OTORISASI'             => NULL,
            'FLAG'                  => NULL,
            'KODE_KANTOR'           => $unitkerja,
            'KODE_PERK_OB'          => NULL,
            'discount'              => NULL
            );
            $this->logAction('insert', $trace_id, $arr_KRETRANS, 'KRETRANS');
            $res_ins_kretrans    = $this->Kre_model->Ins_Tbl('KRETRANS',$arr_KRETRANS);            
            if(!$res_ins_kretrans){
                $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => ''
                );
                $this->logAction('result', $trace_id, $data, 'Problem insert to table kretrans');
                $this->response($data);
            }
            $this->logAction('result', $trace_id, array(), 'KRETRANS -> insert [OK]');
            $res_call_count_kretrans  = $this->Kre_model->Kretrans_count_by_rek($in_no_rekening);
            $this->logAction('select', $trace_id, array(), 'Kre_model->Kretrans_count_by_rek('.$in_no_rekening.')');
            if(!$res_call_count_kretrans){  
                 $arr_upd_ses = array(
                    'status' => '5',
                    'keterangan' => 'errorcode 617',
                    'lastupd' => date('Y-m-d H:i:s')
                );
                $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);

                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);                
                $data = array(
                    'status' => FALSE,
                    'error_code' =>617,
                    'message' => 'Error Internal',
                    'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
                $this->logAction('result', $trace_id, array(), 'Problem query Kre_model->Kretrans_count_by_rek->'.$in_no_rekening);
                $this->response($data);
            } 
            foreach ($res_call_count_kretrans as $sub_call_count_kretrans) {
                $co_REALISASI_POKOK        = $sub_call_count_kretrans->REALISASI_POKOK;
                $co_ANGSURAN_POKOK         = $sub_call_count_kretrans->ANGSURAN_POKOK;
                $co_REALISASI_BUNGA        = $sub_call_count_kretrans->REALISASI_BUNGA;
                $co_ANGSURAN_BUNGA         = $sub_call_count_kretrans->ANGSURAN_BUNGA;
                $co_ANGSURAN_DISCOUNT      = $sub_call_count_kretrans->ANGSURAN_DISCOUNT;
                $co_ANGGARAN_BUNGA_YAD     = $sub_call_count_kretrans->ANGGARAN_BUNGA_YAD;
                $co_ANGSURAN_BUNGA_YAD     = $sub_call_count_kretrans->ANGSURAN_BUNGA_YAD;
                $co_PROVISI                = $sub_call_count_kretrans->PROVISI;
                $co_TRANSAKSI_DEBIUS       = $sub_call_count_kretrans->TRANSAKSI_DEBIUS;
                $co_ANGSURAN_DEBIUS        = $sub_call_count_kretrans->ANGSURAN_DEBIUS;                
            }  
            
        $result_data_nasabah = $this->Kre_model->Kre_nas_join_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_nas_join_by_rek('.$in_no_rekening.')');       
        $arr = array();
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $this->jml_pinjaman       = $sub_res->JML_PINJAMAN ?: '';                
            }
        }
            $this->saldo_akhir  = $this->jml_pinjaman - $co_ANGSURAN_POKOK;
            $arr_kredit = array(
                    'POKOK_SALDO_AKHIR'             => $this->saldo_akhir,
                    'BUNGA_SALDO_AKHIR'             => $co_ANGSURAN_BUNGA,
                    'STATUS'                        => '1',
                    'SALDO_BUNGA_YAD'               => $co_ANGSURAN_BUNGA_YAD,
                    'SALDO_AKHIR_PROVISI'           => $co_PROVISI,
                    'SALDO_AKHIR_DEBIUS'            => $co_ANGSURAN_DEBIUS
                    );
            
            $res_upd_tab = $this->Kre_model->upd_kredit($in_no_rekening,$arr_kredit);
            $this->logAction('update', $trace_id, $arr_kredit, 'KREDIT');
            if(!$res_upd_tab){             
                 $arr_upd_ses = array(
                    'status' => '5',
                    'keterangan' => 'errorcode 617',
                    'lastupd' => date('Y-m-d H:i:s')
                );
                $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
                $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
                $this->Kre_model->Del_kretrans($gen_id_TABTRANS_ID);            
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('response', $trace_id, $data, 'failed, update unsuccessful');
                $this->response($data);
            }
            $this->logAction('result', $trace_id, array(), 'Update KREDIT -> OK');
            $res_call_kredit = $this->Kre_model->Kredit_by_rek($in_no_rekening);
            foreach ($res_call_kredit as $sub_call_kredit) {
                $kre_kd_produk          = $sub_call_kredit->KODE_PRODUK;                
                $kre_kd_integrasi       = $sub_call_kredit->KODE_INTEGRASI;                
            }
            
            $res_call_kre_integrasi     = $this->Kre_model->Kredit_int_by_kode($kre_kd_integrasi);
            foreach ($res_call_kre_integrasi as $sub_call_kre_integrasi) {
                $kre_int_kode_perk_kredit           = $sub_call_kre_integrasi->KODE_PERK_KREDIT;
                $kre_int_kode_perk_kas              = $sub_call_kre_integrasi->KODE_PERK_KAS;
                $kre_int_kode_perk_bunga            = $sub_call_kre_integrasi->KODE_PERK_BUNGA;
                $kre_int_kode_perk_adm_lainya       = $sub_call_kre_integrasi->KODE_PERK_ADM_LAINNYA;
                $kre_int_kode_perk_denda            = $sub_call_kre_integrasi->KODE_PERK_DENDA;
            }
            
            $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($kre_int_kode_perk_kredit,'D');
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total;
            }
            $gen_id_MASTER              = $this->App_model->Gen_id();  
            
            $arr_master = array(
                    'TRANS_ID'              => $gen_id_MASTER,
                    'KODE_JURNAL'           => 'KRE',
                    'NO_BUKTI'              => $in_KUITANSI,
                    'TGL_TRANS'             => $this->_Tgl_hari_ini(),
                    'URAIAN'                => $in_keterangan,
                    'MODUL_ID_SOURCE'       => 'KRE',
                    'TRANS_ID_SOURCE'       => $gen_id_KRETRANS_ID,
                    'USERID'                => $in_agentid,
                    'KODE_KANTOR'           => $unitkerja
                    );
            $this->logAction('insert', $trace_id, $arr_master, 'TRANSAKSI_MASTER');    
            $gen_id_t_detail            = $this->App_model->Gen_id();  

            $total_debet                = $t_pokok + $t_bunga + $t_adm + $t_denda;
//            $arr_t_detail = array(
//                    'TRANS_ID'              => $gen_id_t_detail,
//                    'MASTER_ID'             => $gen_id_MASTER,
//                    'KODE_PERK'             => $in_kode_perk_kas,
//                    'DEBET'                 => $total_debet,
//                    'KREDIT'                => 0
//                    );
            
            $ar_trans_detail = array();
            if(round($t_pokok) == 0){}else{
            $ar_trans_detail[] = array( // kredit
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_kredit, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_pokok);
                }
            if(round($t_bunga) == 0){}else{
            $ar_trans_detail[] = array( // bunga
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_bunga, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_bunga);
                }
            if(round($t_adm) == 0){}else{
            $ar_trans_detail[] = array( // adm
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_adm_lainya, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_adm);
                }
            if(round($t_denda) == 0){}else{
            $ar_trans_detail[] = array( // denda
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_denda, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_denda);
                }
            $ar_trans_detail[] = array( // angsuran
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $in_kode_perk_kas, 
                            'DEBET'         =>  $total_debet, 
                            'KREDIT'        =>  '0');
            
            $this->logAction('insert', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
//            $res_ins_detail    = $this->Kre_model->Ins_Tbl('TRANSAKSI_DETAIL',$arr_t_detail);

            
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            //$this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
            //$this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
            if($res_run){                

                
                $this->logAction('result', $trace_id, array(), 'success, running trasaction');
                $this->logAction('response', $trace_id, array(), '');
//                $this->response($data);
//                return;
            }else{ 
                $arr_upd_ses = array(
                    'status' => '5',
                    'keterangan' => 'errorcode 617',
                    'lastupd' => date('Y-m-d H:i:s')
                );
                $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);

                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);            

                $data = array(
                    'status' => FALSE,
                    'error_code' =>617,
                    'message' => 'Error Internal',
                    'code_angsuran' => '',
                    'data'  => $this->res_kredit_pay
                );
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_KRETRANS_ID.')');

                $this->logAction('response', $trace_id, $data, '');
                $this->response($data);
            }   
        $in_addpoin = array(
            'tid' => $gen_id_KRETRANS_ID,
            'agent' => $in_agentid,
            'kode' => DEFAULT_KODE_TRANS,
            'jenis' => 'KRE',
            'nilai' => $total_debet,
            );
        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
        $res_poin = $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));
        if($res_poin){
            $this->logAction('update', $trace_id, array(), 'result :OK '.urlencode($res_poin));
        }else{
            $this->logAction('update', $trace_id, array(), 'result :NOK'. urlencode($res_poin));
        }
        $arr_upd_ses = array(
                'status' => '1',
                'master_id' => $gen_id_MASTER,
                'keterangan' => 'Sudah diproses',
                'lastupd' => date('Y-m-d H:i:s')
            );
        $this->logAction('update', $trace_id, $arr_upd_ses, 'Update session');
        $this->Kre_model->Upd_kre_angsuran_ses($in_code_trx,$arr_upd_ses);
        $subres  = array(
            'plafon'        => $this->Rp($this->jml_pinjaman),//jml_pinjaman
            'angsuran_ke'   => $angsuran_ke,
            'setoran_pokok' => $this->Rp($t_pokok), //setoran pokok
            'setoran_jasa'  => $this->Rp($t_bunga), //bunga
            'sisa_saldo'    => $this->Rp($this->saldo_akhir) //sisa pinjaman
        );
        
        $data = array(
                    'status' => TRUE,
                    'error_code' =>600,
                    'message' => 'Succesfully',
                    'code_angsuran' => $in_code_trx,
                    'data'  => $subres
                );
        $this->logAction('response', $trace_id, $data, 'Done');
        $this->response($data);
    }
    public function Sent_ang_offline_post() {
        $trace_id = $this->logid();
        $this->logheader($trace_id);
        
        $in_agentid             = $this->input->post('agentid') ?: '';        
        $in_nominal_pokok       = $this->input->post('nominal_pokok') ?: 0;
        $in_nominal_bunga       = $this->input->post('nominal_bunga') ?: 0;
        $in_no_rekening         = $this->input->post('rekid') ?: '';
        
        //cek parameter
        
        if(empty($in_no_rekening)){
            $data = array(
                'status' => FALSE,
                'error_code' =>610,
                'message' => 'Nomor rekening isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            $this->response($data);
            
        }
        if(empty($in_agentid)){
            $data = array(
                'status' => FALSE,
                'error_code' =>612,
                'message' => 'agentid isempty',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            $this->response($data);
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array(
                'status' => FALSE,
                'error_code' =>613,
                'message' => 'agentid invalid',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Failed / data agent not found in databases');
            $this->response($data);
        }
        $result_data_nasabah = $this->Kre_model->Kre_nas_join_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_nas_join_by_rek('.$in_no_rekening.')');       
        $arr = array();
        if($result_data_nasabah){
            foreach($result_data_nasabah as $sub_res){
                $get_nama_nasabah           = $sub_res->nama_nasabah;
                $tgl_jatuh_tempo            = $sub_res->TGL_JATUH_TEMPO;
                $jml_angsuran               = $sub_res->JML_ANGSURAN;             
                $tgl_tagihan                = $sub_res->TGL_TAGIHAN;   
                $this->jml_pinjaman       = $sub_res->JML_PINJAMAN ?: ''; 

            }
            $this->logAction('result', $trace_id, $arr, 'success');            
        }else{
            $data = array(
                'status' => FALSE,
                'error_code' =>611,
                'message' => 'invalid rekening',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'rekid is empty');
            $this->response($data);
        }
        $this->logAction('response', $trace_id, array(), 'Nominal pokok : '.$in_nominal_pokok);

        if($this->Duwet_no_limit($in_nominal_pokok) == FALSE){
            $data = array(
                'status' => FALSE,
                'error_code' =>616,
                'message' => 'Invalid Nominal pokok ['.$in_nominal_pokok.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal_pokok.']');
            $this->response($data);
        }
        if($this->Duwet_no_limit($in_nominal_bunga) == FALSE){
            $data = array(
                'status' => FALSE,
                'error_code' =>623,
                'message' => 'Invalid Nominal bunga ['.$in_nominal_pokok.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal.']');
            $this->response($data);
        }
        if($in_nominal_pokok == 0 && $in_nominal_bunga == 0){
            $data = array(
                'status' => FALSE,
                'error_code' =>616,
                'message' => 'Invalid Nominal pokok ['.$in_nominal_pokok.']',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal_pokok.']');
            $this->logAction('response', $trace_id, $data, 'Invalid Nominal Setoran ['.$in_nominal_bunga.']');
            $this->response($data);
        }
        $result_angsuranke = $this->Kre_model->Kre_angsuran_ke_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_angsuran_ke_by_rek('.$in_no_rekening.')');       
        if($result_angsuranke){
            foreach($result_angsuranke as $sub_ang){
                $angsuran_ke = $sub_ang->ANGSURAN_KE;
            }
        }
        if($angsuran_ke == ''){
            $angsuran_ke = 0;
        }       
        $t_pokok            = $in_nominal_pokok ?: 0;                
        $t_bunga            = $in_nominal_bunga ?: 0;                
        $t_denda            = 0;                
        $t_adm              = 0; 
            
        $get_integrasi = $this->Kre_model->Kode_integrasi_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kode_integrasi_by_rek('.$in_no_rekening.')');
        if($get_integrasi){            
        }else{
            $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('response', $trace_id, $data, 'kode integrasi');
        }
        foreach ($get_integrasi as $sub_integrasi){
            $in_kode_integrasi          = $sub_integrasi->kode_integrasi ?: '';
        }
        $this->logAction('result', $trace_id, array(), 'kode integrasi : '.$in_kode_integrasi);
        $res_call_kode_perk_kas         = $this->Sys_daftar_user_model->Perk_kas($in_agentid);
        $this->logAction('select', $trace_id, array(), 'Sys_daftar_user_model->Perk_kas('.$in_agentid.')');
            if($res_call_kode_perk_kas){
                foreach ($res_call_kode_perk_kas as $sub_call_kode_perk_kas) {
                    $in_kode_perk_kas   = $sub_call_kode_perk_kas->KODE_PERK_KAS;
                }
                $this->logAction('result', $trace_id, array(), 'kode perk kas : '.$in_kode_perk_kas);
            }
            if(empty($in_kode_perk_kas)){
                $in_kode_perk_kas = DEFAULT_KODE_PERK_KAS;
                $this->logAction('result', $trace_id, array(), 'kode perk kas [set default]: '.$in_kode_perk_kas);
            }
            
            $res_call_perk_kode_gord = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kas);
             $this->logAction('select', $trace_id, array(), 'Perk_model->perk_by_kodeperk('.$in_kode_perk_kas.')');
            if($res_call_perk_kode_gord){
                foreach($res_call_perk_kode_gord as $sub_call_perk_kode_gord){
                    $in_nama_perk   = $sub_call_perk_kode_gord->NAMA_PERK;
                    $in_gord        = $sub_call_perk_kode_gord->G_OR_D;
                }
            }
            $res_call_tab_integrasi_by_kd = $this->Kre_model->Integrasi_by_kd_int($in_kode_integrasi);
            foreach ($res_call_tab_integrasi_by_kd as $sub_call_tab_integrasi_by_kd) {

                $in_kode_perk_kas_in_integrasi          =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KAS;
                $in_kode_perk_kredit_in_integrasi       =  $sub_call_tab_integrasi_by_kd->KODE_PERK_KREDIT;
                $in_kode_perk_bunga_in_integrasi        =  $sub_call_tab_integrasi_by_kd->KODE_PERK_BUNGA;                                                      
            }                 
            $res_call_perk_kode_kredit = $this->Perk_model->perk_by_kodeperk($in_kode_perk_kredit_in_integrasi);
            if($res_call_perk_kode_kredit){
                foreach($res_call_perk_kode_kredit as $sub_call_perk_kode_kredit){
                    $in_nama_perk_kredit = $sub_call_perk_kode_kredit->NAMA_PERK;
                    $in_gord_kredit = $sub_call_perk_kode_kredit->G_OR_D;
                }
            }
            $res_call_perk_kode_bunga = $this->Perk_model->perk_by_kodeperk($in_kode_perk_bunga_in_integrasi);
            if($res_call_perk_kode_bunga){
                foreach($res_call_perk_kode_bunga as $sub_call_perk_kode_bunga){
                    $in_nama_perk_bunga         = $sub_call_perk_kode_bunga->NAMA_PERK;
                    $in_gord_bunga              = $sub_call_perk_kode_bunga->G_OR_D;
                }
            }
            
        $gen_id_KRETRANS_ID         = $this->App_model->Gen_id();
        $in_KUITANSI                = $this->_Kuitansi();
        $in_keterangan              = $this->_Kodetrans_by_desc(DEFAULT_KODE_TRANS);
        $in_tob                     = $this->_Kodetrans_by_tob(DEFAULT_KODE_TRANS);
        $this->logAction('result', $trace_id, array(), 'KUITANSI : '.$in_KUITANSI);
        $this->logAction('result', $trace_id, array(), 'KET : '.$in_keterangan);
        $this->logAction('result', $trace_id, array(), 'T O B : '.$in_tob);
        $res_cek_rek                = $this->Kre_model->Kre_nas_join_by_rek($in_no_rekening);
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_nas_join_by_rek('.$in_no_rekening.')');
        if(!$res_cek_rek){
            $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
            $this->logAction('result', $trace_id, $data, '');
            $this->response($data);
            return;
        }
        foreach ($res_cek_rek as $sub_cek_rek) {
            $nama_nasabah   = $sub_cek_rek->nama_nasabah;
        }
        $in_keterangan              = $in_keterangan." ".$angsuran_ke.", No Rek: ".$in_no_rekening.", ".$nama_nasabah;
        $this->logAction('descrp', $trace_id, array(), $in_keterangan);
        $unitkerja                  = DEFAULT_KANTOR;        
        $arr_KRETRANS = array(
            'KRETRANS_ID'           => $gen_id_KRETRANS_ID,
            'TGL_TRANS'             => $this->_Tgl_hari_ini(),
            'NO_REKENING'           => $in_no_rekening,
            'MY_KODE_TRANS'         => DEFAULT_KODE_TRANS,
            'KUITANSI'              => $in_KUITANSI,
            'ANGSURAN_KE'           => $angsuran_ke,
            'POKOK'                 => $t_pokok,
            'BUNGA'                 => $t_bunga,
            'DENDA'                 => $t_denda,
            'ADM_LAINNYA'           => $t_adm,
            'KETERANGAN'            => $in_keterangan,
            'VERIFIKASI'            => '1',
            'USERID'                => $in_agentid,
            'KODE_TRANS'            => DEFAULT_KODE_TRANS,
            'NO_REKENING_TABUNGAN'  => NULL,
            'TOB'                   => $in_tob,
            'ACCRUAL'               => '',
            'Kolek'                 => NULL,
            'OTORISASI'             => NULL,
            'FLAG'                  => NULL,
            'KODE_KANTOR'           => $unitkerja,
            'KODE_PERK_OB'          => NULL,
            'discount'              => NULL
            );
            $this->logAction('insert', $trace_id, $arr_KRETRANS, 'KRETRANS');
            $res_ins_kretrans    = $this->Kre_model->Ins_Tbl('KRETRANS',$arr_KRETRANS);            
            if(!$res_ins_kretrans){
                $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
                $this->logAction('result', $trace_id, $data, 'Problem insert to table kretrans');
                $this->response($data);
            }
            $this->logAction('result', $trace_id, array(), 'KRETRANS -> insert [OK]');
            $res_call_count_kretrans  = $this->Kre_model->Kretrans_count_by_rek($in_no_rekening);
            $this->logAction('select', $trace_id, array(), 'Kre_model->Kretrans_count_by_rek('.$in_no_rekening.')');
            if(!$res_call_count_kretrans){                
                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);                
                $data = array(
                    'status' => FALSE,
                    'error_code' =>617,
                    'message' => 'Error Internal',
                    'code_angsuran' => '',
                    'data'  => $this->res_kredit_pay
                );
                $this->logAction('result', $trace_id, array(), 'Problem query Kre_model->Kretrans_count_by_rek->'.$in_no_rekening);
                $this->response($data);
            } 
            foreach ($res_call_count_kretrans as $sub_call_count_kretrans) {
                $co_REALISASI_POKOK        = $sub_call_count_kretrans->REALISASI_POKOK;
                $co_ANGSURAN_POKOK         = $sub_call_count_kretrans->ANGSURAN_POKOK;
                $co_REALISASI_BUNGA        = $sub_call_count_kretrans->REALISASI_BUNGA;
                $co_ANGSURAN_BUNGA         = $sub_call_count_kretrans->ANGSURAN_BUNGA;
                $co_ANGSURAN_DISCOUNT      = $sub_call_count_kretrans->ANGSURAN_DISCOUNT;
                $co_ANGGARAN_BUNGA_YAD     = $sub_call_count_kretrans->ANGGARAN_BUNGA_YAD;
                $co_ANGSURAN_BUNGA_YAD     = $sub_call_count_kretrans->ANGSURAN_BUNGA_YAD;
                $co_PROVISI                = $sub_call_count_kretrans->PROVISI;
                $co_TRANSAKSI_DEBIUS       = $sub_call_count_kretrans->TRANSAKSI_DEBIUS;
                $co_ANGSURAN_DEBIUS        = $sub_call_count_kretrans->ANGSURAN_DEBIUS;                
            }            
            $arr_kredit = array(
                    'POKOK_SALDO_AKHIR'             => $co_ANGSURAN_POKOK,
                    'BUNGA_SALDO_AKHIR'             => $co_ANGSURAN_BUNGA,
                    'STATUS'                        => '1',
                    'SALDO_BUNGA_YAD'               => $co_ANGSURAN_BUNGA_YAD,
                    'SALDO_AKHIR_PROVISI'           => $co_PROVISI,
                    'SALDO_AKHIR_DEBIUS'            => $co_ANGSURAN_DEBIUS
                    );
            $res_upd_tab = $this->Kre_model->upd_kredit($in_no_rekening,$arr_kredit);
            $this->logAction('update', $trace_id, $arr_kredit, 'KREDIT');
            if(!$res_upd_tab){                            
                $data = array(
                'status' => FALSE,
                'error_code' =>617,
                'message' => 'Error Internal',
                'code_angsuran' => '',
                'data'  => $this->res_kredit_pay
                );
                $this->Kre_model->Del_kretrans($gen_id_TABTRANS_ID);            
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_TABTRANS_ID.')');
                $this->logAction('response', $trace_id, $data, 'failed, update unsuccessful');
                $this->response($data);
            }
            
            $this->saldo_akhir  = $this->jml_pinjaman - $co_ANGSURAN_POKOK;
                        
            $this->logAction('result', $trace_id, array(), 'Update KREDIT -> OK');
            $res_call_kredit = $this->Kre_model->Kredit_by_rek($in_no_rekening);
            foreach ($res_call_kredit as $sub_call_kredit) {
                $kre_kd_produk          = $sub_call_kredit->KODE_PRODUK;                
                $kre_kd_integrasi       = $sub_call_kredit->KODE_INTEGRASI;                
            }
            
            $res_call_kre_integrasi     = $this->Kre_model->Kredit_int_by_kode($kre_kd_integrasi);
            foreach ($res_call_kre_integrasi as $sub_call_kre_integrasi) {
                $kre_int_kode_perk_kredit           = $sub_call_kre_integrasi->KODE_PERK_KREDIT;
                $kre_int_kode_perk_kas              = $sub_call_kre_integrasi->KODE_PERK_KAS;
                $kre_int_kode_perk_bunga            = $sub_call_kre_integrasi->KODE_PERK_BUNGA;
                $kre_int_kode_perk_adm_lainya       = $sub_call_kre_integrasi->KODE_PERK_ADM_LAINNYA;
                $kre_int_kode_perk_denda            = $sub_call_kre_integrasi->KODE_PERK_DENDA;
            }
            
            $res_call_perk_kode_all = $this->Perk_model->perk_by_kodeperk_gord($kre_int_kode_perk_kredit,'D');
            foreach ($res_call_perk_kode_all as $sub_call_perk_kode_all) {
                $count_as_total = $sub_call_perk_kode_all->total;
            }
            $gen_id_MASTER              = $this->App_model->Gen_id();  
            
            $arr_master = array(
                    'TRANS_ID'              => $gen_id_MASTER,
                    'KODE_JURNAL'           => 'KRE',
                    'NO_BUKTI'              => $in_KUITANSI,
                    'TGL_TRANS'             => $this->_Tgl_hari_ini(),
                    'URAIAN'                => $in_keterangan,
                    'MODUL_ID_SOURCE'       => 'KRE',
                    'TRANS_ID_SOURCE'       => $gen_id_KRETRANS_ID,
                    'USERID'                => $in_agentid,
                    'KODE_KANTOR'           => $unitkerja
                    );
            $this->logAction('insert', $trace_id, $arr_master, 'TRANSAKSI_MASTER');    
            $gen_id_t_detail            = $this->App_model->Gen_id();  

            $total_debet                = $t_pokok + $t_bunga + $t_adm + $t_denda;

            $ar_trans_detail = array();
            if(round($t_pokok) == 0){}else{
            $ar_trans_detail[] = array( // kredit
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_kredit, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_pokok);
                }
            if(round($t_bunga) == 0){}else{
            $ar_trans_detail[] = array( // bunga
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_bunga, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_bunga);
                }
            if(round($t_adm) == 0){}else{
            $ar_trans_detail[] = array( // adm
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_adm_lainya, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_adm);
                }
            if(round($t_denda) == 0){}else{
            $ar_trans_detail[] = array( // denda
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $kre_int_kode_perk_denda, 
                            'DEBET'         =>  0, 
                            'KREDIT'        =>  $t_denda);
                }
            $ar_trans_detail[] = array( // angsuran
                            'TRANS_ID'      =>  $this->App_model->Gen_id(), 
                            'MASTER_ID'     =>  $gen_id_MASTER, 
                            'KODE_PERK'     =>  $in_kode_perk_kas, 
                            'DEBET'         =>  $total_debet, 
                            'KREDIT'        =>  '0');
            
            $this->logAction('insert', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
//            $res_ins_detail    = $this->Kre_model->Ins_Tbl('TRANSAKSI_DETAIL',$arr_t_detail);

            
            $res_run = $this->Trans->Run_transaction('TRANSAKSI_MASTER',$arr_master,'TRANSAKSI_DETAIL',$ar_trans_detail);
            //$this->logAction('transaction', $trace_id, $arr_master, 'TRANSAKSI_MASTER');
            //$this->logAction('transaction', $trace_id, $ar_trans_detail, 'TRANSAKSI_DETAIL');
            if($res_run){                

                
                $this->logAction('result', $trace_id, array(), 'success, running trasaction');
                $this->logAction('response', $trace_id, array(), '');
//                $this->response($data);
//                return;
            }else{            
                $this->Kre_model->Del_kretrans($gen_id_KRETRANS_ID);            
                $data = array('status' => FALSE,'error' => 'Gagal transaction', 'data' => '');
                $this->logAction('result', $trace_id, array(), 'failed, running trasaction');
                $this->logAction('delete', $trace_id, array(), 'rolback transaction, Tab_model->Del_by_tabtrans('.$gen_id_KRETRANS_ID.')');

                $this->logAction('response', $trace_id, $data, '');
                $this->response($data);
            }   
        $in_addpoin = array(
            'tid' => $gen_id_KRETRANS_ID,
            'agent' => $in_agentid,
            'kode' => DEFAULT_KODE_TRANS,
            'jenis' => 'KRE',
            'nilai' => $total_debet,
            );
        $this->logAction('update', $trace_id, $in_addpoin, 'ADD POIN');
        $this->Poin_model->Ins_his_reward(json_encode($in_addpoin));        
        
        $arr_upd_ses = array(
                'status' => '1',
                'master_id' => $gen_id_MASTER,
                'keterangan' => 'Sudah diproses',
                'lastupd' => date('Y-m-d H:i:s')
            );
        $this->logAction('update', $trace_id, $arr_upd_ses, 'Update session');

        $in_codeangsuran = trim($in_agentid).$this->_rand_id();
        $kre_angsuran = array(
                        'no_rekening'       => $in_no_rekening,
                        'nama'              => $get_nama_nasabah,
                        'total_setoran'     => $total_debet,
                        'total_tunggakan'   => 0,
                        'tunggakan_pokok'   => 0,
                        'tunggakan_bunga'   => 0,
                        'tunggakan_adm'     => 0,
                        'tunggakan_denda'   => 0,
                        'total_tagihan'     => $total_debet,
                        'tagihan_pokok'     => $t_pokok,
                        'tagihan_bunga'     => $t_bunga,
                        'tagihan_adm'       => $t_adm,
                        'tagihan_denda'     => $t_denda,
                        'angsuran_ke'       => $angsuran_ke,
                        'jml_angsuran'      => $jml_angsuran,
                        'status'            => '1',
                        'keterangan'        => 'pembayaran anggsuran offline',
                        'userid'            => $in_agentid,
                        'code_trx'          => $in_codeangsuran,
                        'master_id'         => $gen_id_MASTER,
                        'lastupd'           => date('Y-m-d H:i:s')
            );
        $this->Kre_model->Ins_Tbl('kre_angsuran_ses_e',$kre_angsuran);
        $this->logAction('insert', $trace_id, $kre_angsuran, 'data session :Kre_model->Ins_Tbl(kre_angsuran_ses_e)'); 

        $subres  = array(
            'plafon'        => $this->Rp($this->jml_pinjaman),//jml_pinjaman
            'angsuran_ke'   => $angsuran_ke,
            'setoran_pokok' => $this->Rp($t_pokok), //setoran pokok
            'setoran_jasa'  => $this->Rp($t_bunga), //bunga
            'sisa_saldo'    => $this->Rp($this->saldo_akhir) //sisa pinjaman
        );   
            
        $data = array(
                    'status' => TRUE,
                    'error_code' =>600,
                    'message' => 'Succesfully',
                    'code_angsuran' => $in_codeangsuran,
                    'data'  => $subres
                );
        $this->logAction('response', $trace_id, $data, 'Done');
        $this->response($data);
    }
    function is_JSON($data) {
        $this->post_data = json_decode( stripslashes( $data ) );
        if( $this->post_data === NULL )
        {
            return false;
        }
        return true;
    }
    protected function _Kodetrans_by_desc($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Kre_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_desc          = $res_desc->deskripsi;
                }
        }else{
            $out_desc   = "";
        }
        return $out_desc;
    }
    protected function _Check_postdata() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $data = array('status' => FALSE,'error' => 'Request method failed');
            $this->response($data);
            return;
        }
        if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );               
        if(empty($HTTP_RAW_POST_DATA)){
            $data = array('status' => FALSE,'error' => 'Parameter not found');
            $this->response($data);
            return;
        }
    }
    protected function _Kodetrans_by_tob($in_kd_trans = NULL) {
        $kodetrans_desc = $this->Kre_model->Kodetrans_by_kodetrans($in_kd_trans);
        if($kodetrans_desc){
            foreach($kodetrans_desc as $res_desc){
                    $out_tob          = $res_desc->TOB;
                }
        }else{
            $out_tob   = NULL;
        }
        return $out_tob;
    }
    protected function _Kuitansi($out_nokwi = '') {
        $result_kwitn = $this->Kre_model->Kwitansi();
            if($result_kwitn){
                foreach($result_kwitn as $res_kwi){
                    $out_nokwi          = $res_kwi->KUITANSI;
                }
                
            }
            if($out_nokwi == ''){
                $out_nokwi = "Pby.0001";
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
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }
    protected function Star_date() {
        $hari_ini = date("Y-m-d");
        $tgl_pertama = date('01 F Y', strtotime($hari_ini));
        return $tgl_pertama;
    }
    protected function Last_date($tgl) {
        $hari_ini = $tgl." ".date("F Y");
        return $hari_ini;
    }
    function Rp($value)    {
        return number_format($value,2,",",".");
    }
    protected function _rand_id() {       
        $in_random = random_string('alnum', 10); 
        if(empty($in_random)){
            $in_random = random_string('alnum', 10);
        }
        return date('ymdHi').$in_random;
    }
    public function Mutasi_post() {
        $trace_id   = $this->logid();
        $this->logheader($trace_id);
        
        $no_rekening                = $this->input->post('no_rekening');
        $in_periode                 = $this->input->post('periode');
        $in_agentid                 = $this->input->post('agentid');
        $arr_err = array();
        $arr_err[] = array(
                "tgl_trans"=> "",
                "sandi"=> "",
                "keterangan"=> "",
                "nominal"=> ""
             );
        if(empty($in_agentid)){
            $data = array('status' => FALSE,'error_code' => '611','message' => 'agent isempty', 'riwayat_angsuran' => $arr_err);
            $this->logAction('response', $trace_id, $data, 'agentid isempty');
            $this->response($data);
        }
        if(empty($no_rekening)){
            $data = array('status' => FALSE,'error_code' => '613','message' => 'no_rekening isempty', 'riwayat_angsuran' => $arr_err);            
            $this->logAction('response', $trace_id, $data, 'no_rekening is empty');
            $this->response($data);
        }
        if(empty($in_periode)){   
            
            $data = array('status' => FALSE,'error_code' => '614','message' => 'periode is empty', 'riwayat_angsuran' => $arr_err);
            $this->logAction('response', $trace_id, $data, 'periode is empty');
            $this->response($data);
            
        }
        if($this->Admin_user2_model->User_by_id($in_agentid)){            
        }else{
            $data = array('status' => FALSE,'error_code' => '612','message' => 'agentid invalid', 'riwayat_angsuran' => $arr_err);
            
            $this->logAction('response', $trace_id, $data, 'agent not found in databases');
            $this->response($data);
        }
        $res_call_history = $this->Kre_model->Kre_periode($no_rekening,$in_periode); 
        $arr_res = array();
        if($res_call_history){
            foreach ($res_call_history as $res_history) {
                
               
                $arr_res[] = array(
                    'tgl_trans'          => $res_history->tgl_trans ?: '0000-00-00',
                    'sandi'              => $res_history->sandi ?: '',
                    'pokok'              => $this->Rp($res_history->pokok ?: 0),
                    'basil'              => $this->Rp($res_history->basil ?: 0),
                    'keterangan'         => $res_history->keterangan ?: ''
                );
            }
        }else{
            
            
            $data = array('status' => FALSE,'error_code' => '615','message' => 'data isempty', 'riwayat_angsuran' => $arr_err);           
            $this->logAction('response', $trace_id, $data, 'data not found in databases');
            $this->response($data);
        }
        
        $this->logAction('select', $trace_id, array(), 'Kre_model->Kre_periode('.$no_rekening.','.$in_periode.')');
        
        $data = array(
                'status' => TRUE,
                'error_code' => '600',
                'message' => 'success',
                'riwayat_angsuran' => $arr_res
                );
        
        $this->logAction('response', $trace_id, $data, 'Done'); 
        $this->response($data);
        
        
    }
}
