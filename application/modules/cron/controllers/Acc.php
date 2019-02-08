<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Generate
 *
 * @author edisite
 */
class Acc extends API_Controller{
    //put your code here
    //var $count_total = 0;
    var $count_saldo_awal;
    var $count_saldo_akhi;
    public function __construct() {
        parent::__construct();  
        //$this->load->model('Trf_model');
        $this->load->model('Acc_model');
    }
    public function Neraca_harian_get() {
        //
        $this->transid  = $this->Trxid() ?: '';
        $this->Log_write($this->transid, 'begin', '');        
        $datalog = array('uri' => $this->uri->uri_string(),
                'method' => $this->request->method,
                'params' => $this->_args ? ($this->config->item('rest_logs_json_params') === TRUE ? json_encode($this->_args) : serialize($this->_args)) : NULL,
                'api_key' => isset($this->rest->key) ? $this->rest->key : '',
                'key_session'    => $this->input->get_request_header('key_ses'),
                'time' => time());
        //$set_tanggal = $this->_Tgl_hari_ini() ?: '0000-00-00';
        
        $set_tanggal = '2018-03-08';
        
        
        $this->Log_write($this->transid, ' parameter', str_replace(array("\n", "\r", "    "), '', print_r($datalog, true)));                    
        $this->Log_write($this->transid, ' set tanggal', $set_tanggal);                    
        $res_check = $this->Acc_model->Count_report_perkiraan($set_tanggal);
        if($res_check){
            foreach ($res_check as $val) {
                $count_total    = $val->total;                         
            }
            $this->Log_write($this->transid, ' count tanggal',' succes total : '.$count_total);
        }else{
            $this->Log_write($this->transid, ' count perkiraan', ' error count perkiraan Count_report_perkiraan()');
            $data = array('error_code' => '01','message' => 'error count neraca');
            $this->response($data);  
        }        
        if($count_total == 0){    
            $this->Log_write($this->transid, ' new temp',' if count = 0 : create new perkiran');
            $res_crt_perk = $this->Acc_model->Ins_report_perkiran($set_tanggal);            
            if(!$res_crt_perk){      
                $this->Log_write($this->transid, ' new temp',' gagal create new perkiraan');
                $data = array('error_code' => '03','message' => 'invalid insert perkiraan');
                $this->response($data);  
            }
        }       
        $this->Log_write($this->transid, ' new temp',' get Perkiraan_by_gord()');
        $res_crt_perk = $this->Acc_model->Perkiraan_by_gord($set_tanggal);
        if(!$res_crt_perk){
            $this->Log_write($this->transid, ' perkiraan',' data exist, so not ruonning cronjob');
            $data = array('error_code' => '03','message' => 'Data exist');
            $this->response($data);  
        }
        $this->Log_write($this->transid, ' perkiraan','total = '.  count($res_crt_perk, COUNT_RECURSIVE));
        $this->Log_write($this->transid, ' perkiraan','parsing : '.str_replace(array("\n", "\r", "    "), '', print_r($res_crt_perk, true)));
        foreach ($res_crt_perk as $val) {
            $g_kodeperk     = $val->KODE_PERK;
            $g_idperk       = $val->ID_PERK;
            $g_typeperk     = $val->TYPE_PERK;
            $g_levelperk    = $val->LEVEL_PERK;
            $g_dork         = $val->D_OR_K;
            $this->Log_write($this->transid, ' perkiraan','call : Proces_by_kodeperk('.$g_kodeperk.','.$set_tanggal.','.$g_idperk.')');
            //$res_p_by_kperk =$this->Proces_by_kodeperk($this->transid,$g_kodeperk,$set_tanggal,$g_idperk);
            //
            //---------------------------------------------------
            //
            //
            //
            //perubahanya dibawa ini
            //
            //
            $res_p_by_kperk =$this->Acc_model->Saldo_dk($g_kodeperk,$set_tanggal);
            if($res_p_by_kperk){
                 $this->Log_write($this->transid, ' result','OK');
            }else{
                 $this->Log_write($this->transid, ' result','faild = '.  count($res_p_by_kperk, COUNT_RECURSIVE));
            }
            //----------------
            
            
            
        }
        $this->Log_write($this->transid, 'finish', '');
        $data = array('error_code' => '00','message' => 'ok');         
        $this->response($data); 
        //done
    }
    function Proces_by_kodeperk($trxid = '',$i_kodeperk = '',$in_tanggal = '',$g_idperk ='') {
        $this->transid = $trxid ?: random_string('alnum', '20');
        if(empty($i_kodeperk)){            return;        }
        if(empty($in_tanggal)){            return;        }
        if(empty($g_idperk)){            return;        }
        $this->Log_write($this->transid, ' fnc',$this->uri->uri_string().'Proces_by_kodeperk');
        $this->Log_write($this->transid, ' call model','Trans_detail_saldo_awal('.$in_tanggal.','.$i_kodeperk.')');
        $res_saldo_awal = $this->Acc_model->Trans_detail_saldo_awal($in_tanggal,$i_kodeperk);
        if(!$res_saldo_awal){            return;        }
        foreach ($res_saldo_awal as $val_awal) {
            $awal_deb   =   $val_awal->debet;
            $awal_kre   =   $val_awal->kredit;
        }
        $res_saldo_awal = $this->Acc_model->Trans_detail_dork($in_tanggal,$i_kodeperk);
        if(!$res_saldo_awal){            return;        }
        foreach ($res_saldo_awal as $val_awal) {
            $tday_deb   =   $val_awal->debet;
            $tday_kre   =   $val_awal->kredit;
        }
        $awal_deb = $awal_deb ?: def_nilai_nol;
        $awal_kre = $awal_kre ?: def_nilai_nol;
        $tday_deb = $tday_deb ?: def_nilai_nol;
        $tday_kre = $tday_kre ?: def_nilai_nol;
        $count_saldo_awal =  $awal_deb - $awal_kre;
        $count_saldo_akhi =  $count_saldo_awal + $tday_deb - $tday_kre;
        
        $arr_upd = array(
          'saldo_akhir1'=> $count_saldo_awal,  //saldo awal
          'saldo_akhir2'=> $tday_deb,  //debet today
          'saldo_akhir3'=> $tday_kre,  //kredit today
          'saldo_akhir4'=> $count_saldo_akhi,  //saldo akhir hari ini
          'flag_last_upd'        => date('Y-m-d H:i:s'),  //saldo akhir hari ini
          'flag'        => '1'  //saldo akhir hari ini
        );
        $this->Log_write($this->transid, ' perkiraan','call : Upd_temp_neraca_harian = '.  json_encode($arr_upd).')');
        
        //saldo_akhir1=160000000, saldo_akhir2=75000000, saldo_akhir3=0, saldo_akhir4=235000000
        $this->Acc_model->Upd_temp_neraca_harian($i_kodeperk,  $in_tanggal,$arr_upd); 
        $this->Log_write($this->transid, ' perkiraan','call : Proces_by_idperk('.$in_tanggal.','. $g_idperk.')');
        //$this->Proces_by_idperk($this->transid,$in_tanggal, $g_idperk);
        
    }
    function Proces_by_idperk($trxid = '',$in_tanggal = '',$g_idperk = '') {
        $this->transid = $trxid ?: random_string('alnum', '20');
        $this->Log_write($this->transid, ' fnc',$this->uri->uri_string().'/proces_by_idperk');
        $this->Log_write($this->transid, ' fnc','tanggal ='. $in_tanggal.'&id_perk='.$g_idperk);
        if(empty($g_idperk)){            return;        }
        if(empty($in_tanggal)){            return;        }
        $this->Log_write($this->transid, ' call model','Perkiraan_idinduk_by_kdperk ='. $in_tanggal.'&id_perk='.$g_idperk);
        $res_id_perk = $this->Acc_model->Perkiraan_idinduk_by_kdperk($g_idperk,$in_tanggal);
        if(!$res_id_perk){            
            $this->Log_write($this->transid, ' result','failed='.$res_id_perk);
            return;        }
        foreach ($res_id_perk as $val_perk) {
            $id_induk   =   $val_perk->ID_INDUK;
        }
        
        $id_induk = $id_induk ?: def_nilai_nol;
        $this->Log_write($this->transid, ' id induk',$id_induk);
        $this->Log_write($this->transid, ' call','Trans_main_induk()'.$id_induk);
        $res_induk_perk = $this->Acc_model->Trans_main_induk($id_induk);
        if(!$res_induk_perk){            
            $this->Log_write($this->transid, ' result','error'.$res_induk_perk);
            return;        }
        foreach ($res_induk_perk as $val_induk) {
            $induk_AWAL     =   $val_induk->AWAL;
            $induk_DEBET    =   $val_induk->DEBET;
            $induk_KREDIT   =   $val_induk->KREDIT;
            $induk_AKHIR    =   $val_induk->AKHIR;
        }
        $induk_AWAL     = $induk_AWAL   ?: def_nilai_nol;
        $induk_DEBET    = $induk_DEBET  ?: def_nilai_nol;
        $induk_KREDIT   = $induk_KREDIT ?: def_nilai_nol;
        $induk_AKHIR    = $induk_AKHIR  ?: def_nilai_nol;
        
        $arr_upd = array(
          'saldo_akhir1'=> $induk_AWAL,  //saldo awal
          'saldo_akhir2'=> $induk_DEBET,  //debet today
          'saldo_akhir3'=> $induk_KREDIT,  //kredit today
          'saldo_akhir4'=> $induk_AKHIR,  //saldo akhir hari ini
          'flag_last_upd'        => date('Y-m-d H:i:s'),  //saldo akhir hari ini
          'flag'        => '1'  //saldo akhir hari ini
        );
        $this->Log_write($this->transid, ' perkiraan','call : Upd_temp_neraca_harian '.  json_encode($arr_upd));
        $this->Acc_model->Upd_temp_neraca_harian_by_id_perk($id_induk,  $in_tanggal,$arr_upd);
        
    }
    protected function _Tgl_hari_ini(){
        return Date('Y-m-d');
    }        
    function Rp($value)    {
        return number_format($value,2,",",".");
    }
    function Log_write($ptrxid, $psubject, $pmsg)
    {
            $path = DIR_LOG.LOG_PFX.LOG_SFX.date(LOG_PTN).".log";
            $objfile = fopen($path, "a");
                    chmod($path, 0775);
                    $pmsg = str_replace("\n", "", $pmsg);
                    $pmsg = str_replace("\m", "\n", $pmsg);
                    fprintf($objfile, "%s|cron|%-8s|%-15s|%s\n", date("Y-m-d H:i:s"), $ptrxid, $psubject, $pmsg);
            fclose($objfile);
    }
    
}
DEFINE('def_nilai_nol', 0);
DEFINE("LOG_PFX", "");
DEFINE("LOG_PTN", "Y-m-d");
DEFINE("LOG_SFX", "ACC_");
DEFINE("DIR_LOG", "");
