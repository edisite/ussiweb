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
class Generate extends MY_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();  
        $this->load->model('Trf_model');
        $this->load->model('Tab_model');
    }
    public function Pdf($in_code) {
        if(empty($in_code)){
            return FALSE;
        }
        $json = FALSE;
        $res_data = $this->Trf_model->Bkt_trf($in_code);
        if($res_data){ 
            //pegawe 	bank_pengirim 	rekening_sender 	nama_sender 	bank_penerima 	
            //rekening_receiver 	nama_receiver 	nominal 	cost_adm 
            $data = array();
            foreach ($res_data as $val){
                $data['rekening_sender']        = $val->rekening_sender;
                $data['nama_sender']            = $val->nama_sender;
                $data['rekening_receiver']      = $val->rekening_receiver;
                $data['nama_receiver']          = $val->nama_receiver;
                $data['code']                   = $val->code_transfer;
                $data['nominal']                = $val->nominal;
                $data['cost_adm']               = $val->cost_adm;
                $data['pegawe']                 = $val->pegawe;
                $data['bank_pengirim']                 = $val->bank_pengirim;
                $data['bank_penerima']                 = $val->bank_penerima;
                $data['tgl_transfer']                 = $val->dtm;
            }
            header('Content-Type: application/json');             
            echo json_encode($data);
            RETURN;
//            $this->output
//                ->set_status_header(200)
//                ->set_content_type('application/json', 'utf-8')
//                ->set_output(json_encode($res_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                //->_display();
        }
        return false;
       
    }
    public function Trx_print($in_nomor_rekening = "") {
        if(empty($in_nomor_rekening)){
            return false;
        }
       
        $gmin   = "";
        $gmax   = "";
        $res_print_id   = $this->Tab_model->Tabtrans_print_id($in_nomor_rekening);
        if($res_print_id){
            foreach ($res_print_id as $valid) {
                $gmin = $valid->min;
                $gmax = $valid->max;
            }
        }else{
            return FALSE;
        }
        $norow  = 0;
        $data   = array();
        $tid    = array();
        $res_print      = $this->Tab_model->Tabtrans_print($in_nomor_rekening);
        if($res_print){
            foreach ($res_print as $val) {
                if($val->print_buku == "Y"){                    
                }else{
                    $data[] =  array(
                        'tgl'       => $val->Tanggal,
                        'sandi'     => $val->Sandi,
                        'debet'     => number_format($val->Debet,2,",","."),
                        'kredit'    => number_format($val->Kredit,2,",","."),
                        'saldo'     => number_format($val->SALDO,2,",","."),
                        'user'      => ""
                    );
                    $tid[] = array('TABTRANS_ID'=>$val->TABTRANS_ID,'PRINT_BUKU' => 'Y');
                    $norow = $norow + 1;
                }
                
            }
            $wokeh = array(
                    'norek' => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'row'           => $norow,
                    'trx'           => $data
            );
            $wosess = array(
                    'rek' => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'rws'           => $norow
            );
            $wosess = array(
                    'rek'           => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'rws'           => $norow,
                    'tgl'           => mdate('%Y-%m-%d %H:%i:%s', time())
            );
            if($tid){
               $this->Tab_model->Bukutab_upd($tid);
            }else{
                return FALSE;
            }
            if(!$norow){
                return FALSE;
            }
            
            $this->Tab_model->Lastprint($wosess,$in_nomor_rekening);
            
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($wokeh, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
               // ->_display();
            
        }        
        return false;
    }
    public function Trx_last_print($in_nomor_rekening = "") {
        if(empty($in_nomor_rekening)){
            return false;
        }
        $gmin   = "";
        $gmax   = "";
        $res_print_id   = $this->Tab_model->Ceklastprint($in_nomor_rekening);
        if($res_print_id){
            foreach ($res_print_id as $valid) {
                $gmin = $valid->min;
                $gmax = $valid->max;
            }
        }else{
            return "FALSE";
        }
        $norow  = 0;
        $data   = array();
        $tid    = array();
        $res_print      = $this->Tab_model->Tabtrans_print_last($in_nomor_rekening,$gmin,$gmax);
        if($res_print){
            foreach ($res_print as $val) {
                if($val->print_buku == "Y"){                    
                }else{
                    $data[] =  array(
                        'tgl'       => $val->Tanggal,
                        'sandi'     => $val->Sandi,
                        'debet'     => number_format($val->Debet,2,",","."),
                        'kredit'    => number_format($val->Kredit,2,",","."),
                        'saldo'     => number_format($val->SALDO,2,",","."),
                        'user'      => ""
                    );
                    $tid[] = array('TABTRANS_ID'=>$val->TABTRANS_ID,'PRINT_BUKU' => 'Y');
                    $norow = $norow + 1;
                }
                
            }
            $wokeh = array(
                    'norek' => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'row'           => $norow,
                    'trx'           => $data
            );
            $wosess = array(
                    'rek' => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'rws'           => $norow
            );
            if($tid){
               // $this->Tab_model->Bukutab_upd($tid);
            }else{
                return FALSE;
            }
            if(!$norow){
                return FALSE;
            }
             $this->Tab_model->Lastprint($wosess,$in_nomor_rekening);
            
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($wokeh, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
               // ->_display();
            
        }        
        return "false";
    }
    public function Tab_setor($in_code) {
        if(empty($in_code)){
            return FALSE;
        }
        $json = FALSE;
        $res_data = $this->Tab_model->Tab_by_kodetrans($in_code);
        if($res_data){ 
            $data = array(); //[{"tgl_trans":"2010-04-12","no_rekening":"35.01.000030","pokok":"30000.0000",
            //                  "adm":"0.00","MY_KODE_TRANS":"100",
            //                  "SALDO_AKHIR":"5477641.00","nama_nasabah":"Meiniyah","ALAMAT":"Krisik RT 03\/05"}]
            foreach ($res_data as $val){
                $data['tgl']        = $val->tgl_trans;
                $data['rekening']            = $val->no_rekening;                
                $data['nama']          = $val->nama_nasabah;
                $data['code_trans']             = $val->KODE_TRANS;
                $data['nominal']                = $val->pokok;
                $data['cost_adm']               = $val->adm;

                $data['alamat']                 = $val->ALAMAT;
                $data['keterangan']                 = $val->keterangan;
                $data['tabtrans_id']                 = $val->tabtrans_id;
            }
//            header('Content-Type: application/json');             
//            echo json_encode($data);
//            RETURN;
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                //->_display();
        }
        return false;
       
    }
    public function Kre_Angsuran($code_angsuran = '') {
        if(empty($code_angsuran)){
            return FALSE;
        }
        $json = FALSE;
        $res_data = $this->Kre_model->Kre_angsuran_bukti($code_angsuran);
        if($res_data){ 
            $data = array(); 
            foreach ($res_data as $val){
                $data['tgl']            = $val->TGL_TRANS;
                $data['rekening']       = $val->NO_REKENING;                
                $data['nama']           = $val->nama;
                $data['code_trans']     = $val->KODE_TRANS;
                $data['nominal']        = $this->Rp($val->nominal);
                $data['pokok']          = $this->Rp($val->POKOK);
                $data['bunga']          = $this->Rp($val->BUNGA);
                $data['adm']            = $this->Rp($val->ADM_LAINNYA);
                $data['denda']          = $this->Rp($val->DENDA);
                $data['angsuran']       = $val->ANGSURAN_KE;
                $data['keterangan']     = $val->KETERANGAN;
                $data['agentid']        = $val->USERID;
            }
//            header('Content-Type: application/json');             
//            echo json_encode($data);
//            RETURN;
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                //->_display();
        }
        return false;
       
    }
    public function Com_Pulsa($code = '') {
        if(empty($code)){
            return FALSE;
        }
        $json = FALSE;
        $res_data = $this->Pay_model->Logpulsa($code);
        if($res_data){ 
            $data = array(); 
            foreach ($res_data as $val){
                $data['tgl']            = $val->dtm;
                $data['msisdn']             = $val->msisdn;                
                $data['product']           = $val->product;
                $data['provider']     = $val->provider;
                $data['product_alias']        = $val->product_alias;
                $data['agentid']        = $val->userid;
                $data['sn']        = $val->res_sn;
            }
            header('Content-Type: application/json');             
            echo json_encode($data);
            return;
        }
        return false;
       
    }    
    function Rp($value)    {
        return number_format($value,2,",",".");
    }
    
}
