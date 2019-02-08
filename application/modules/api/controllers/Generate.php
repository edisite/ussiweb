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
    //bukti transfer
    public function Pdf($in_code) {
        if(empty($in_code)){
            return FALSE;
        }
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1" || $this->input->ip_address() == "10.1.1.62"){           
        }else{            return false;        }
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
                $data['bank_pengirim']          = $val->bank_pengirim;
                $data['bank_penerima']          = $val->bank_penerima;
                $data['tgl_transfer']           = $val->dtm;
            }
            header('Content-Type: application/json');             
            echo json_encode($data);
            RETURN;
        }
        return false;
       
    }
    public function Pdf_trf_bmt_kebank_lain($in_code = '') {
        if(empty($in_code)){
            return FALSE;
        }
//        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
//        }else{            return "false";        }
        $json = FALSE;
        $res_data = $this->Trf_model->Bkt_trf_antar_bank($in_code);
        
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
                $data['nominal']                = $this->Rp($val->nominal);
                $data['cost_adm']               = $this->Rp($val->cost_adm);
                $data['pegawe']                 = $val->pegawe;
                $data['bank_pengirim']          = $val->bank_pengirim;
                $data['bank_penerima']          = $val->bank_penerima;
                $data['tgl_transfer']           = $val->dtm;
                $data['t_tgl']                  = $val->t_tgl;
                $data['t_jam']                  = $val->t_jam;
                $data['t_berita']               = $val->t_berita;
                $data['t_noref']                = $val->t_noref;
                $data['t_status']               = $val->t_status;
                $data['t_admin_id']             = $val->t_admin_id;
            }
            header('Content-Type: application/json');             
            echo json_encode($data);
            RETURN;
        }
        return "false";
       
    }
    public function Trx_print($in_nomor_rekening = "") {
        if(empty($in_nomor_rekening)){
            return "NOK";
        }
        //mencari total yang belum di print
        $get_total_no_print = $this->Tab_model->Print_total_noprint($in_nomor_rekening);
        if($get_total_no_print){
            foreach ($get_total_no_print as $v) {
                $total_belum_print = $v->total;
            }
        }else{
            return "NOK";
        }
        
        $gmin   = "";
        $gmax   = "";
        $data   = array();
        $tid    = array();
        $norow  = 0;
        
        //menentukan max transaksi untuk saldo cutoff
        if($total_belum_print > 20){           
            //get limit  ex 5 transaksi terakhir
            $res_print_id   = $this->Tab_model->Tabtrans_print_limit($in_nomor_rekening);
            if($res_print_id){
                foreach ($res_print_id as $valid) { $gmin = $valid->MIN; $gmax = $valid->MAX; }                
            }else{                return FALSE;            }
            //get tabtrans_id  start 
            $res_print_id   = $this->Tab_model->Tabtrans_print_id($in_nomor_rekening);
            if($res_print_id){
                foreach ($res_print_id as $valid) { $gmin_start = $valid->min;  }                
            }else{                return FALSE;            }
            
            $res_saldoawal = 0;
            $req_saldo_awal = $this->Tab_model->Tabtrans_print_saldo_awal($in_nomor_rekening,$gmin_start);
            if($req_saldo_awal){
                foreach ($req_saldo_awal as $val_saldoawal) {
                        $res_saldoawal =  $val_saldoawal->total_saldo ?: 0;
                }
            }
            //get mutasi cutoff from gmin_start -> gmin
            $res_print_cutoff      = $this->Tab_model->Tabtrans_print_last_cutoff($in_nomor_rekening,$gmin_start,$gmin,$res_saldoawal);
            if($res_print_cutoff){
                $co_debebt = 0;
                $co_kredit = 0;
                foreach ($res_print_cutoff as $val_co) {
                        $co_tanggal =  $val_co->Tanggal;
                        $co_sandi   =  $val_co->Sandi;
                        $co_debebt  =  $co_debebt + $val_co->Debet;
                        $co_kredit  =  $co_kredit + $val_co->Kredit;
                        $co_saldo   =  $val_co->SALDO;                        
                    $tid[] = array('TABTRANS_ID'=>$val_co->TABTRANS_ID,'PRINT_BUKU' => 'Y');
                    $norow = $norow + 1;
                }
                $data['0'] =  array(
                        'tgl'       => $val_co->Tanggal,
                        'sandi'     => "GTU",
                        'debet'     => number_format($co_debebt,2,",","."),
                        'kredit'    => number_format($co_kredit,2,",","."),
                        'saldo'     => number_format($co_saldo,2,",","."),
                        'user'      => ""
                    );
            }
            else{ return "NOK";            }
            
            //get print last tabungan
            $res_print      = $this->Tab_model->Tabtrans_print_last($in_nomor_rekening,$gmin,$gmax,$co_saldo);
        }else{
            $res_print      = $this->Tab_model->Tabtrans_print($in_nomor_rekening);
            $res_print_id   = $this->Tab_model->Tabtrans_print_id($in_nomor_rekening);
            if($res_print_id){
                foreach ($res_print_id as $valid) {
                    $gmin = $valid->min;
                    $gmax = $valid->max;
                }                
            }else{
                return "NOK";
            }            
        }
        
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
                    'norek'         => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'row'           => $norow,
                    'trx'           => $data
            );
//            $wosess = array(
//                    'rek' => $in_nomor_rekening,
//                    'min'           => $gmin,
//                    'max'           => $gmax,
//                    'rws'           => $norow
//            );
            $wosess = array(
                    'rek'           => $in_nomor_rekening,
                    'min'           => $gmin,
                    'max'           => $gmax,
                    'rws'           => $norow,
                    'tgl'           => mdate('%Y-%m-%d %H:%i:%s', time())
            );
            if($tid){
               //$this->Tab_model->Bukutab_upd($tid);
            }else{
                return "NOK";
            }
            if(!$norow){
                return "NOK";
            }
            
            $this->Tab_model->Lastprint($wosess,$in_nomor_rekening);
            
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($wokeh, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
               // ->_display();
            
        }        
        return "NOK";
    }
    public function Trx_last_print($in_nomor_rekening = "") {
        if(empty($in_nomor_rekening)){
            return false;
        }
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
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
        return "false";
    }
    public function Tab_setor($in_code) {
        if(empty($in_code)){
            return FALSE;
        }
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
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
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
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
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
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
    public function Com_Pln($code = '') {
        if(empty($code)){
            return FALSE;
        }
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
        $json = FALSE;
        $res_data = $this->Pay_model->Logpln($code);
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
    public function Com_Pln_postpaid($code = '') {
        if(empty($code)){
            return FALSE;
        }
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
        $json = FALSE;
        $res_data = $this->Pay_model->Logpln_postpaid($code);
        if($res_data){ 
            $data = array(); 
            foreach ($res_data as $val){
                $data['tgl']            = $val->dtm ?: '';
                $data['nometer']        = $val->destid ?: '';                
                $data['periode']        = $val->periode1 ?: '';
                $data['nama']           = $val->cust_name ?: '';
                $data['tarif']          = $val->tariff ?: '';
                $data['daya']           = $val->daya ?: '';
                $data['tagihan']        = $this->Rp($val->tagihan);
                $data['adm']            = $this->Rp($val->biaya_adm);
                $data['total']          = $this->Rp($val->total);
                $data['sn']             = $val->res_sn ?: '';                
                $data['transaksi_id']   = $code ?: '';                
                
      
            }
            header('Content-Type: application/json');             
            echo json_encode($data);
            return;
        }
        return false;
       
    }
    
    public function Com_Finance($code = '') {
        if(empty($code)){            return false;        }
//        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
//        }else{            return "nok";        }

        $res_data = $this->Pay_model->Logfinance($code);
        if($res_data){ 
            $data = array(); 
            foreach ($res_data as $val){
                $data['dtm']                = $val->dtm;
                $data['custid']             = $val->cust_id;                
                $data['product']            = $val->product;
                $data['leasing']            = $val->leasing;
                $data['userid']             = $val->userid;
                $data['cust_name']          = $val->cust_name;
                $data['amount']             = $this->Rp($val->amount);
                $data['admin_fee']          = $this->Rp($val->admin_fee);
                $data['total']              = $this->Rp($val->total);
                $data['res_sn']             = $val->res_sn;
                $data['ke']                 = $val->no_installment;
                $data['jt']                 = $val->due_date;
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
    public function Com_report_all() {
        $in_dtm_from        = $this->input->get('dtm_from') ?: '';
        $in_dtm_to          = $this->input->get('dtm_to') ?: '';
        
        $res_inv    = $this->Com_model->Rep_by_dtm($in_dtm_from,$in_dtm_to);        
        if($res_inv){
        header('Content-Type: application/json');             
            echo json_encode($res_inv);
            return;
        }
        return false;
    }
    public function Com_report_mitra() {
        $in_dtm_from        = $this->input->get('dtm_from') ?: '';
        $in_dtm_to          = $this->input->get('dtm_to') ?: '';
        $in_mitra           = $this->input->get('mitra') ?: '';
        
        $res_inv    = $this->Com_model->Rep_by_mitra($in_dtm_from,$in_dtm_to,$in_mitra);        
        if($res_inv){
        header('Content-Type: application/json');             
            echo json_encode($res_inv);
            return;
        }
        return false;
    }
    public function Com_report_product() {
        $in_dtm_from        = $this->input->get('dtm_from') ?: '';
        $in_dtm_to          = $this->input->get('dtm_to') ?: '';
        $in_mitra           = $this->input->get('mitra') ?: '';
        $in_product         = $this->input->get('product') ?: '';
        
        $res_inv    = $this->Com_model->Rep_by_product($in_dtm_from,$in_dtm_to,$mitra,$in_product);        
        if($res_inv){
        header('Content-Type: application/json');             
            echo json_encode($res_inv);
            return;
        }
        return false;
    }    
    public function Inq_com() {
        $get_data_pending = $this->Com_model->Trx_pending_data();
        if($get_data_pending){            
        }else{            
            return "NOK";
        }
        $res = array();
        foreach ($get_data_pending as $v) {
            $gmsisdn    = $v->msisdn;
            $gproductid = $v->product;
            $gdatetme   = $v->dtm;
            $gtransid   = $v->master_id;
            $guserid    = $v->userid;
            $getres = $this->Inquirypending($gmsisdn, $gdatetme,$gtransid,$guserid,$gproductid);
            if($getres){
                $res[] = $gmsisdn.' - '.$gproductid.'  -  OK';
            }else{
                $res[] = $gmsisdn.' - '.$gproductid.'  -  NOK';
                //sleep(1);
            }
        }        
        var_dump($res);
    }    
    function Hitget($url = '')    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    protected function Inquirypending($msisdn = '',$gdatetme = '',$in_transid = '',$guserid = '',$gproductid = '') {
        if(empty($msisdn)){
            return false;
        }
        if(empty($gdatetme)){
            return false;
        }
        if(empty($in_transid)){
            return false;
        }
        if(empty($guserid)){
            return false;
        }
        if(empty($gproductid)){
            return false;
        }
        if(strlen($msisdn) > 20){
            return false;
        }
        $URL = URL_INQ_PENDING;
        $URL = $URL."?no_hp=".$msisdn;
        $res_call_hit_topup = $this->Hitget($URL);
        if(!$res_call_hit_topup){
            return false;
        }
        $data_topup = json_decode($res_call_hit_topup);
        $topup_status           = isset($data_topup->status) ? $data_topup->status : '' ;
        $topup_respon_code      = isset($data_topup->response_code) ? $data_topup->response_code : '';
        $topup_message          = isset($data_topup->message) ? $data_topup->message : '';
        $topup_saldo            = isset($data_topup->saldo) ? $data_topup->saldo : NULL;
        $topup_no_serial        = isset($data_topup->sn) ? $data_topup->sn : NULL;
        $topup_kode_produk      = isset($data_topup->kode_produk) ? $data_topup->kode_produk : NULL;
        $topup_harga      = isset($data_topup->harga) ? $data_topup->harga : NULL;
        
        //var_dump($res_call_hit_topup);
        if(trim($topup_kode_produk) == $gproductid){}else{
            return false;
        }
        //{"status":"OK","response_code":"00","keterangan":"SUKSES","message":"05\/09\/17 06:50 ISI HAX5 KE 083863853772,
        // BERHASIL SISA.SAL=6.247.565,HRG=5.650,ID=61675927,SN=71181552684561;","kode_produk":" HAX5 ","harga":"5650",
        // "saldo":"6247565","sn":"71181552684561","provider":"Powermedia"
        
        switch (trim($topup_respon_code)):
        case "00": $status = "OK"; $message = "INQ Transaction Approved"; break;
        case "30": $status = "NOK"; $message = "INQ Nomor Salah"; break;
        case "40": $status = "NOK";$message = "INQ Gagal(global)"; break;
        case "89": $status = "NOK";$message = "INQ Gagal Koneksi ke Operator"; break;
        case "91": $status = "NOK";$message = "INQ Database problem"; break;
        case "05": $status = "NOK";$message = "INQ Internal Error"; break;
        case "92": $status = "WAIT"; $message = "INQ Inquiry tidak ditemukan, cek di webtools"; break;
        case "13": $status = "NOK";$message = "INQ Product Close"; break;
        case "51": $status = "NOK";$message = "INQ Saldo tidak mencukupi"; break;
        case "15": $status = "NOK";$message = "INQ Billing not available"; break;
        case "68": $status = "WAIT";$message = "INQ Pending"; break;
        case "50": $status = "WAIT";$message = "INQ Duplicat transaction, cek di webtools"; break;
        default:
            $status = "WAIT"; $message = "INQ unknow status error";
        endswitch;
        
        if($status == "OK"){
            $dataupd = array(
                    'res_code'      => '00',
                    'res_saldo'     => $topup_saldo,
                    'res_sn'        => $topup_no_serial,
                    'res_message'   => $topup_message,
                    'price_fr_mitra'   => $topup_harga,
                    'desc_trx'      => $message.' #'.Date('YmdHis')
                );
        }elseif($status == "NOK"){
            $dataupd = array(
                    'res_code'      => $topup_respon_code,                    
                    'res_message'   => $topup_message,
                    'desc_trx'      => $message.' #'.Date('YmdHis')
                );
                
                $resdel = $this->Com_model->Del_Comtrans_byid($in_transid,$guserid);
                if($resdel){
                    
                }  else {
                    return false;
                }
        }elseif($status == "WAIT"){
            $dataupd = array(
                    'res_code'      => $topup_respon_code,                    
                    'res_message'   => $topup_message,
                    'desc_trx'      => $message.' #'.Date('YmdHis')
                );
        }else{
            $dataupd = array(                  
                    'res_message'   => $topup_message,
                    'desc_trx'      => $message.' #'.Date('YmdHis')
                );
        }
        $resupd = $this->Com_model->Upd_com_log($topup_kode_produk,$msisdn,$gdatetme,$dataupd);
        if($resupd){
            return true;
        }else{
            return false;
        }
    }
    
    public function Updharga() {
        $sql = "SELECT product_id,price_stok FROM sejahtera.com_pulsa";
        $q = $this->db->query($sql)->result();
        if($q){
            foreach ($q as $v) {
                $p = $v->product_id;
                $st = $v->price_stok;
                $this->Upd($st, $p);
            }
        } 
    }
    function Upd($stok,$pro) {
        $sql ="UPDATE com_pulsa_log SET price_stok = '".$stok."' WHERE product = '".$pro."'";
        $this->db->query($sql);
        return true;
    }
    public function Fcm_device_remove_session() {
        $this->App_model->Device_del();
    }
    public function Sync_comtrans() {
        
        $res_no_masterid = $this->Com_model->GetDataNoMasterID();
        if(!$res_no_masterid){
            
        }else{
        $no = 1;
        
        foreach ($res_no_masterid as $v) {
            $waktu              = $v->dtm;
            $r_provider         = $v->nominal;
            $in_msisdn          = $v->msisdn;
            $r_price_selling    = $v->price_selling;
            $topup_no_serial    = $v->res_sn; 
            $in_agenid          = $v->userid;
            $trace_id           = $v->log_trace;         
        
        
            $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
            $gen_id_COMMON_ID               = $this->App_model->Gen_id();
            $in_keterangan                  = 'Commerce '." ".strtoupper($r_provider)." ".$in_msisdn." ".  $this->Rp($r_price_selling)." SN:.".$topup_no_serial;

            

            $arr_data = array(
                'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
                'TGL_TRANS'         =>$waktu,
                'NOCUSTOMER'        =>$in_msisdn,
                'COMTYPE'           =>$r_provider,
    //            'NO_REKENING'       =>$in_rkning, 
                'MY_KODE_TRANS'     =>'100', 
                'KUITANSI'          =>'000', 
                'POKOK'             =>$r_price_selling,
                'ADM'               =>'0',
                'KETERANGAN'        =>$in_keterangan, 
                'VERIFIKASI'        =>'1', 
                'USERID'            =>$in_agenid, 
                'KODE_TRANS'        =>'101',
                'TOB'               =>'T', 
                'SANDI_TRANS'       =>'',
                'KODE_PERK_OB'      =>'', 
                'NO_REKENING_VS'    =>'', 
                'KODE_KOLEKTOR'     =>'000',
                'KODE_KANTOR'       =>'35',
                'ADM_PENUTUPAN'     =>'0.00',
                'jam'               =>Date('Y-m-d H:i:'),
                'COMMON_ID'         =>$gen_id_COMMON_ID,
                'MODEL'         =>'COM'
                );
            $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);      

            $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'System upd masterid '.$gen_id_TABTRANS_ID.' on '.Date('YmdHis'));
            $this->Pay_model->Upd_logpulsa($trace_id,$dataupd); 
            echo $no;
            $no++;
        }
        }
        
        $res_no_masterid = $this->Com_model->GetDataPayNoMasterID();
        if(!$res_no_masterid){
            
        }else{
//        header('Content-Type: application/json');             
//            echo json_encode($res_no_masterid);
//            return;
        $no = 1;
        
        foreach ($res_no_masterid as $v) {
            $waktu              = $v->dtm;
            $r_provider         = $v->nominal;
            $in_msisdn          = $v->destid;
            $tagihan            = $v->tagihan;
            $topup_no_serial    = $v->res_sn; 
            $in_agenid          = $v->userid;
            $trace_id           = $v->log_trace;   
            
            $padm_indosis       = $v->adm_indosis;    
            $padm_bmt           = $v->adm_bmt;    
            $padm_agen          = $v->adm_agen;          
            $padm_provider      = $v->adm_provider;    

            $totaladm = $padm_indosis + $padm_bmt + $padm_agen + $padm_provider;
        
            $gen_id_TABTRANS_ID             = $this->App_model->Gen_id();
            $gen_id_COMMON_ID               = $this->App_model->Gen_id();
            $in_keterangan                  = 'Payment '." ".strtoupper($r_provider)." ".$in_msisdn." ".  $this->Rp($tagihan + $totaladm)." SN:.".$topup_no_serial;

            

            $arr_data = array(
                'COMTRANS_ID'       =>$gen_id_TABTRANS_ID, 
                'TGL_TRANS'         =>$waktu,
                'NOCUSTOMER'        =>$in_msisdn,
                'COMTYPE'           =>$r_provider,
    //            'NO_REKENING'       =>$in_rkning, 
                'MY_KODE_TRANS'     =>'100', 
                'KUITANSI'          =>'000', 
                'POKOK'             =>$tagihan,
                'ADM'               =>$totaladm,
                'KETERANGAN'        =>$in_keterangan, 
                'VERIFIKASI'        =>'1', 
                'USERID'            =>$in_agenid, 
                'KODE_TRANS'        =>'101',
                'TOB'               =>'T', 
                'SANDI_TRANS'       =>'',
                'KODE_PERK_OB'      =>'', 
                'NO_REKENING_VS'    =>'', 
                'KODE_KOLEKTOR'     =>'000',
                'KODE_KANTOR'       =>'35',
                'ADM_PENUTUPAN'     =>'0.00',
                'jam'               =>Date('Y-m-d H:i:'),
                'COMMON_ID'         =>$gen_id_COMMON_ID,
                'MODEL'             =>'PAY'
                );
            $res_ins    = $this->Tab_model->Ins_Tbl('COMTRANS',$arr_data);      

            $dataupd = array('master_id' => $gen_id_TABTRANS_ID,'desc_trx' => 'System upd masterid '.$gen_id_TABTRANS_ID.' on '.Date('YmdHis'));
            $this->Pay_model->Upd_logpayment($trace_id,$dataupd); 
            echo $no;
            $no++;
        }
        }
        
        
    }
    
    //1. print bpjs
    public function Com_bpjs($code = '') {
        if(empty($code)){
            return FALSE;
        }
        if($this->input->ip_address() == "localhost" || $this->input->ip_address() == "127.0.0.1"){           
        }else{            return false;        }
         
        
        $json = FALSE;
        $res_data = $this->Com_model->Getdata_bpjs_by_logtrace($code);
        
        if($res_data){ 
            $data = array(); 
            foreach ($res_data as $val){
                $data['tgl']            = $val->dtm ?: '';
                $data['bpjsid']         = $val->destid ?: '';                
                $data['premi']          = $val->periode." BULAN" ?: '';
                $data['nama']           = $val->cust_name ?: '';
                $data['tagihan']        = $this->Rp($val->POKOK);
                $data['adm']            = $this->Rp($val->ADM);
                $data['total']          = $this->Rp($val->total_tagihan);
                $data['sn']             = $val->res_sn ?: '';                
                $data['transaksi_id']   = $code ?: '';                
                
            }
            header('Content-Type: application/json');             
            echo json_encode($data);   
        }       
    }
    
    //jalanin otomatis untuk merubah transaksi transa detail dari tarik ke storan 
    
    public function Tabtrans_cari() {
        $sql = "SELECT * FROM tabtrans WHERE tgl_trans >= '2018-03-01' and tgl_trans <= '2018-03-31' and my_kode_trans = '200' AND kode_trans IN ('225','226','227','228','229','230','231','232','233','234','235') ORDER BY tabtrans_id ASC";
        //$qry = $this->db->query($sql)->result();
        $qry = FALSE;
        if($qry){
            foreach ($qry as $v) {
                $ttrans         = $v->TABTRANS_ID;
                $myct           = $v->MY_KODE_TRANS;
                if($myct == "200"){
                    //$this->Trans_master($ttrans, $myct);
                }
            }
            echo "OK";
            return;
        }
        echo "KOSONG";        
    }
    function Trans_master($trans, $mct) {
        if(empty($trans)){            return;
        }
        if(empty($mct)){            return;
        }
        
        $sql = "SELECT * FROM transaksi_master WHERE trans_id_source = '".$trans."'";
        $qry = $this->db->query($sql)->result();
        
        foreach ($qry as $v) {
            $ttransid = $v->TRANS_ID;   
            if($mct == "200"){
                return $this->Trans_detail($ttransid);
            }
        }      
        
    }
    function Trans_detail($transid) {
        $sql = "SELECT * FROM transaksi_detail WHERE master_id ='".$transid."'";
        $qry = $this->db->query($sql)->result();
        if($qry){
            $debet = 0;
            $kredit = 0;
            foreach ($qry as $v) {
                $trans_id = '';
                $trans_id   = $v->TRANS_ID;
                $kodeperk   = $v->KODE_PERK;
                $debet      = $v->DEBET;
                $kredit     = $v->KREDIT;
                
                if($kodeperk == "20201"){
                    if(intval($kredit) != 0){
                        $sql = "UPDATE transaksi_detail SET keterangan = 'system upd 220318',DEBET = '0',KREDIT = '".$kredit."' WHERE TRANS_ID = '".$trans_id."' and master_id ='".$transid."'";
                        $this->db->query($sql);
                    }
                }
                if($kodeperk == "10101"){
                    if(intval($debet) != 0){
                        $sql = "UPDATE transaksi_detail SET keterangan = 'system upd 220318' ,DEBET = '".$debet."',KREDIT = '0' WHERE TRANS_ID = '".$trans_id."' and master_id ='".$transid."'";
                        $this->db->query($sql);
                    }
                }
            }
            return;
        }
    }
}
//inquiry transaction commerce
define('URL_INQ_PENDING', 'http://10.1.1.62/API_BMT_NEW/request_inquiry.php');